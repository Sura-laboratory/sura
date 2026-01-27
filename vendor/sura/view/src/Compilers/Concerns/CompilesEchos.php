<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesEchos
{
    /**
     * Compile the "regular" echo statements. {{ }}
     *
     * @param string $value
     * @return string
     */
    protected function compileRegularEchos(string $value): string
    {
        $pattern = \sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->contentTags[0], $this->contentTags[1]);
        $callback = function ($matches) {
            $whitespace = empty($matches[3]) ? '' : $matches[3] . $matches[3];
            $wrapped = \sprintf($this->echoFormat, $this->compileEchoDefaults($matches[2]));
            return $matches[1] ? \substr($matches[0], 1) : $this->phpTagEcho . $wrapped . '; ?>' . $whitespace;
        };
        return \preg_replace_callback($pattern, $callback, $value);
    }

    /**
     * Compile the escaped echo statements. {!! !!}
     *
     * @param string $value
     * @return string
     */
    protected function compileEscapedEchos(string $value): string
    {
        $pattern = \sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->escapedTags[0], $this->escapedTags[1]);
        $callback = function ($matches) {
            $whitespace = empty($matches[3]) ? '' : $matches[3] . $matches[3];

            return $matches[1] ? $matches[0] : $this->phpTag
                . \sprintf($this->echoFormat, $this->compileEchoDefaults($matches[2])) . '; ?>'
                . $whitespace;
            //return $matches[1] ? $matches[0] : $this->phpTag
            // . 'echo static::e(' . $this->compileEchoDefaults($matches[2]) . '); ? >' . $whitespace;
        };
        return \preg_replace_callback($pattern, $callback, $value);
    }

    /**
     * Compile View echos into valid PHP.
     *
     * @param string $value
     * @return string
     */
    protected function compileEchos(string $value): string
    {
        foreach ($this->getEchoMethods() as $method => $length) {
            $value = $this->$method($value);
        }
        return $value;
    }
}