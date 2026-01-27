<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesComments
{
    /**
     * Compile View comments into valid PHP.
     *
     * @param string $value
     * @return string
     */
    protected function compileComments(string $value): string
    {
        $pattern = \sprintf('/%s--(.*?)--%s/s', $this->contentTags[0], $this->contentTags[1]);
        return \preg_replace($pattern, $this->phpTag . '/*$1*/ ?>', $value);
    }
}