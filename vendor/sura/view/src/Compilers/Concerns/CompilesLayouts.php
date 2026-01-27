<?php
declare(strict_types=1);

namespace Sura\View\Compilers\Concerns;

/**
 * Trait CompilesLayouts
 * @package Sura\View\Compilers\Concerns
 */
trait  CompilesLayouts
{

    /**
     * Compile the extends statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileExtends(string $expression): string
    {
        $expression = $this->stripParentheses($expression);
        // $_shouldextend avoids to runchild if it's not evaluated.
        // For example @if(something) @extends('aaa.bb') @endif()
        // If something is false then it's not rendered at the end (footer) of the script.
        $this->uidCounter++;
        $data = $this->phpTag . 'if (isset($_shouldextend[' . $this->uidCounter . '])) { echo $this->runChild(' . $expression . '); } ?>';
        $this->footer[] = $data;
        return $this->phpTag . '$_shouldextend[' . $this->uidCounter . ']=1; ?>';
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileSection(string $expression): string
    {
        return $this->phpTag . "\$this->startSection{$expression}; ?>";
    }

    /**
     * Execute the @parent command. This operation works in tandem with extendSection
     *
     * @return string
     * @see extendSection
     */
    protected function compileParent(): string
    {
        return $this->PARENTKEY;
    }

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileYield(string $expression): string
    {
        return $this->phpTagEcho . "\$this->yieldContent{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @return string
     */
    protected function compileShow(): string
    {
        return $this->phpTagEcho . '$this->yieldSection(); ?>';
    }

    /**
     * Compile the append statements into valid PHP.
     *
     * @return string
     */
    protected function compileAppend(): string
    {
        return $this->phpTag . '$this->appendSection(); ?>';
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @return string
     */
    protected function compileOverwrite(): string
    {
        return $this->phpTag . '$this->stopSection(true); ?>';
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @return string
     */
    protected function compileStop(): string
    {
        return $this->phpTag . '$this->stopSection(); ?>';
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndsection(): string
    {
        return $this->phpTag . '$this->stopSection(); ?>';
    }
}