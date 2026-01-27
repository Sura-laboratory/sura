<?php
declare(strict_types=1);

namespace Sura\View\Compilers\Concerns;


trait CompilesAuthorizations
{
    /**
     * @param $expression
     * @return string
     */
    protected function compileCan($expression): string
    {
        $v = $this->stripParentheses($expression);
        return $this->phpTag . 'if (call_user_func($this->authCallBack,' . $v . ')): ?>';
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileElseCan($expression = ''): string
    {
        $v = $this->stripParentheses($expression);
        if ($v) {
            return $this->phpTag . 'elseif (call_user_func($this->authCallBack,' . $v . ')): ?>';
        }

        return $this->phpTag . 'else: ?>';
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileCannot($expression): string
    {
        $v = $this->stripParentheses($expression);
        return $this->phpTag . 'if (!call_user_func($this->authCallBack,' . $v . ')): ?>';
    }

    /**
     * Compile the elsecannot statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileElseCannot($expression = ''): string
    {
        $v = $this->stripParentheses($expression);
        if ($v) {
            return $this->phpTag . 'elseif (!call_user_func($this->authCallBack,' . $v . ')): ?>';
        }

        return $this->phpTag . 'else: ?>';
    }

    /**
     * Compile the canany statements into valid PHP.
     * canany(['edit','write'])
     *
     * @param $expression
     * @return string
     */
    protected function compileCanAny($expression): string
    {
        $role = $this->stripParentheses($expression);
        return $this->phpTag . 'if (call_user_func($this->authAnyCallBack,' . $role . ')): ?>';
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @param $expression
     * @return string
     */
    protected function compileElseCanAny($expression): string
    {
        $role = $this->stripParentheses($expression);
        if ($role == '') {
            return $this->phpTag . 'else: ?>';
        }
        return $this->phpTag . 'elseif (call_user_func($this->authAnyCallBack,' . $role . ')): ?>';
    }

    /**
     * Compile the end-can statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndcan(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the end-can statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndcanany(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the end-cannot statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndcannot(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

}