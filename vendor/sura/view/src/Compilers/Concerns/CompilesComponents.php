<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesComponents
{
    /**
     * Compile the component statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileComponent(string $expression): string
    {
        return $this->phpTag . " \$this->startComponent{$expression}; ?>";
    }

    /**
     * Compile the end-component statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndComponent(): string
    {
        return $this->phpTagEcho . '$this->renderComponent(); ?>';
    }

    /**
     * Compile the slot statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileSlot($expression): string
    {
        return $this->phpTag . " \$this->slot{$expression}; ?>";
    }

    /**
     * Compile the end-slot statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSlot(): string
    {
        return $this->phpTag . ' $this->endSlot(); ?>';
    }

}