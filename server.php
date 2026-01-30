<?php
// server.php
// Swoole WebSocket server that authenticates a user by user_id+key and returns new messages.
// Requirements: Swoole extension with coroutine MySQL enabled, PHP 7.1+ (or PHP 8+ recommended).

use Swoole\WebSocket\Server;
use Swoole\Coroutine;
use Swoole\Coroutine\MySQL;

$host = '0.0.0.0';
$port = 9502;

// Replace with your DB connection info
$dbConfig = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'yourdbuser',
    'password' => 'yourdbpass',
    'database' => 'yourdb',
    'timeout' => 2,
];

$server = new Server($host, $port);

$server->on('start', function (Server $server) use ($host, $port) {
    echo "Swoole WebSocket server started at ws://{$host}:{$port}\n";
});

$server->on('open', function (Server $server, $req) {
    echo "Connection opened: fd={$req->fd}\n";
});

$server->on('message', function (Server $server, $frame) use ($dbConfig) {
    // Run DB work inside a coroutine to use Swoole coroutine MySQL.
    Coroutine::create(function () use ($server, $frame, $dbConfig) {
        $fd = $frame->fd;
        $data = json_decode($frame->data, true);
        if (!is_array($data) || empty($data['action'])) {
            $server->push($fd, json_encode(['error' => 'invalid_payload', 'msg' => 'Expected JSON with an action field']));
            return;
        }

        if ($data['action'] !== 'search_new') {
            $server->push($fd, json_encode(['error' => 'unknown_action']));
            return;
        }

        // Required inputs
        $userId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
        $apiKey = isset($data['key']) ? trim($data['key']) : '';
        $convoKey = isset($data['convo_key']) ? trim($data['convo_key']) : null;
        $markRead = !empty($data['mark_read']); // optional: mark found messages as seen

        if ($userId <= 0 || $apiKey === '') {
            $server->push($fd, json_encode(['action' => 'search_new', 'ok' => false, 'error' => 'missing_credentials']));
            return;
        }

        // Connect to MySQL using coroutine client
        $db = new MySQL();
        $ok = $db->connect($dbConfig);
        if ($ok === false) {
            $server->push($fd, json_encode(['action' => 'search_new', 'ok' => false, 'error' => 'db_connect_failed', 'detail' => $db->connect_error ?? null]));
            return;
        }

        // Authenticate user (user must exist and api_key must match)
        $authSql = "SELECT id FROM users WHERE id = ? AND api_key = ? LIMIT 1";
        $stmt = $db->prepare($authSql);
        if ($stmt === false) {
            // fallback: execute raw query with escaped params
            $uidEsc = $db->escape((string)$userId);
            $keyEsc = $db->escape($apiKey);
            $authRes = $db->query("SELECT id FROM users WHERE id = {$uidEsc} AND api_key = '{$keyEsc}' LIMIT 1");
        } else {
            $authRes = $db->execute($stmt, [$userId, $apiKey]);
        }

        if (empty($authRes)) {
            $server->push($fd, json_encode(['action' => 'search_new', 'ok' => false, 'error' => 'auth_failed']));
            $db->close();
            return;
        }

        // Build query for unseen messages
        $sql = "SELECT id, sender_id, recipient_id, convo_key, payload, created_at FROM messages WHERE recipient_id = ? AND seen = 0";
        $params = [$userId];
        if ($convoKey !== null && $convoKey !== '') {
            $sql .= " AND convo_key = ?";
            $params[] = $convoKey;
        }
        $sql .= " ORDER BY created_at ASC";

        $stmt2 = $db->prepare($sql);
        if ($stmt2 === false) {
            // fallback: escape params manually
            $uidEsc = $db->escape((string)$userId);
            $extra = '';
            if ($convoKey !== null && $convoKey !== '') {
                $ckEsc = $db->escape($convoKey);
                $extra = " AND convo_key = '{$ckEsc}'";
            }
            $sqlRaw = "SELECT id, sender_id, recipient_id, convo_key, payload, created_at FROM messages WHERE recipient_id = {$uidEsc} AND seen = 0{$extra} ORDER BY created_at ASC";
            $res = $db->query($sqlRaw);
        } else {
            $res = $db->execute($stmt2, $params);
        }

        if ($res === false) {
            $server->push($fd, json_encode(['action' => 'search_new', 'ok' => false, 'error' => 'db_query_failed']));
            $db->close();
            return;
        }

        // Send results
        $server->push($fd, json_encode(['action' => 'search_new', 'ok' => true, 'count' => count($res), 'messages' => $res]));

        // Optionally mark returned messages as seen
        if ($markRead && !empty($res)) {
            $ids = array_map(function($m) { return (int)$m['id']; }, $res);
            $idsList = implode(',', $ids);
            $db->query("UPDATE messages SET seen = 1 WHERE id IN ({$idsList})");
        }

        $db->close();
    });
});

$server->on('close', function (Server $server, $fd) {
    echo "Connection closed: fd={$fd}\n";
});

$server->start();