<?php

declare(strict_types=1);

namespace Mozg\classes;

/**
 * Класс для отправки email-сообщений.
 *
 * Использует встроенную функцию `mail()` для отправки HTML-писем.
 * Заголовки устанавливаются как HTML с кодировкой UTF-8 и адресом отправителя по умолчанию.
 */
class Email
{
    /**
     * Отправляет HTML-письмо указанному получателю.
     *
     * Подготавливает стандартные заголовки: From, CC, MIME-Version и Content-Type (text/html; charset=UTF-8).
     * Адрес отправителя по умолчанию: `noreply@mixchat.ru`.
     *
     * Примечания:
     *  - Параметр `$to` должен быть валидным адресом электронной почты.
     *  - Сообщение передаётся как HTML; при необходимости экранировать пользовательский ввод.
     *  - Функция не возвращает результат отправки (void). При необходимости можно расширить логику для обработки ошибки `mail()`.
     *
     * @param string $to     Email получателя (например: `user@example.com`)
     * @param string $subject Тема письма
     * @param string $message HTML-содержимое письма
     * @return void
     */
    public static function send(string $to, string $subject, string $message): void
    {
        $headers = 'From: ' . strip_tags('noreply@mixchat.ru') . "\r\n";
//                    $headers .= "Reply-To: ". strip_tags('noreply@mixchat.ru') . "\r\n";
        $headers .= "CC: noreply@mixchat.ru\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $subject, $message, $headers);
    }
}
