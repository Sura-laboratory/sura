<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesJson
{
    /**
     * @param $expression
     * @return string
     */
    protected function compileJSon($expression): string
    {
        $parts = \explode(',', $this->stripParentheses($expression));
        $options = isset($parts[1]) ? \trim($parts[1]) : JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
        $depth = isset($parts[2]) ? \trim($parts[2]) : 512;
        return $this->phpTagEcho . " json_encode($parts[0], $options, $depth); ?>";
    }
}