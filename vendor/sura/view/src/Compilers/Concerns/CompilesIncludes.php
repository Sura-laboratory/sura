<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesIncludes
{
    /**
     * Compile the each statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileEach(string $expression): string
    {
        return $this->phpTagEcho . "\$this->renderEach{$expression}; ?>";
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileInclude(string $expression): string
    {
        $expression = $this->stripParentheses($expression);
        return $this->phpTagEcho . '$this->runChild(' . $expression . '); ?>';
    }

    /**
     * It loads an compiled template and paste inside the code.<br>
     * It uses more disk space but it decreases the number of includes<br>
     *
     * @param $expression
     * @return string
     * @throws Exception
     */
    protected function compileIncludeFast($expression): string
    {
        $expression = $this->stripParentheses($expression);
        $ex = $this->stripParentheses($expression);
        $exp = \explode(',', $ex);
        $file = $this->stripQuotes(isset($exp[0]) ? $exp[0] : null);
        $fileC = $this->getCompiledFile($file);
        if (!@\file_exists($fileC)) {
            // if the file doesn't exist then it's created
            $this->compile($file, true);
        }
        return $this->getFile($fileC);
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileIncludeIf(string $expression): string
    {
        return $this->phpTag . 'if ($this->templateExist' . $expression . ') echo $this->runChild' . $expression . '; ?>';
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileIncludeWhen(string $expression): string
    {
        $expression = $this->stripParentheses($expression);
        return $this->phpTagEcho . '$this->includeWhen(' . $expression . '); ?>';
    }

    /**
     * Compile the includefirst statement
     *
     * @param string $expression
     * @return string
     */
    protected function compileIncludeFirst(string $expression): string
    {
        $expression = $this->stripParentheses($expression);
        return $this->phpTagEcho . '$this->includeFirst(' . $expression . '); ?>';
    }
}