<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesStacks
{
    /**
     * Compile the stack statements into the content.
     *
     * @param string $expression
     * @return string
     */
    protected function compileStack(string $expression): string
    {
        return $this->phpTagEcho . "\$this->yieldPushContent{$expression}; ?>";
    }

    /**
     * Compile the endpush statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndpush(): string
    {
        return $this->phpTag . '$this->stopPush(); ?>';
    }

    /**
     * Compile the endpushonce statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndpushOnce(): string
    {
        return $this->phpTag . '$this->stopPush(); endif; ?>';
    }

    /**
     * Compile the endpush statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndPrepend(): string
    {
        return $this->phpTag . '$this->stopPrepend(); ?>';
    }
}