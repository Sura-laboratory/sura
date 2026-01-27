<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesHelpers
{
    /**
     * @param null $expression
     * @return string
     */
    protected function compilecsrf($expression = null): string
    {
        $expression = ($expression === null) ? "'_token'" : $expression;
        return "<input type='hidden' name='{$this->phpTag} echo {$expression}; ?>' value='{$this->phpTag}echo \$this->csrf_token; " . "?>'/>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileDd($expression): string
    {
        return $this->phpTagEcho . " '<pre>'; var_dump$expression; echo '</pre>';?>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileMethod($expression): string
    {
        $v = $this->stripParentheses($expression);

        return "<input type='hidden' name='_method' value='{$this->phpTag}echo $v; " . "?>'/>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileDump($expression): string
    {
        return $this->phpTagEcho . " \$this->dump{$expression};?>";
    }
}