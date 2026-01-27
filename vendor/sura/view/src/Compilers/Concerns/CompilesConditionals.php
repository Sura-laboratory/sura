<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesConditionals
{
    /**
     * Compile the end-auth statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndAuth(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the guest statements into valid PHP.
     *
     * @param null $expression
     * @return string
     */
    protected function compileGuest($expression = null): string
    {
        if ($expression === null) {
            return $this->phpTag . 'if(!isset($this->currentUser)): ?>';
        }

        $role = $this->stripParentheses($expression);
        if ($role == '') {
            return $this->phpTag . 'if(!isset($this->currentUser)): ?>';
        }

        return $this->phpTag . "if(!isset(\$this->currentUser) || \$this->currentRole!={$role}): ?>";
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @param $expression
     * @return string
     */
    protected function compileElseGuest($expression): string
    {
        $role = $this->stripParentheses($expression);
        if ($role == '') {
            return $this->phpTag . 'else: ?>';
        }

        return $this->phpTag . "elseif(!isset(\$this->currentUser) || \$this->currentRole!={$role}): ?>";
    }

    /**
     * /**
     * Compile the end-auth statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndGuest(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the unless statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileUnless(string $expression): string
    {
        return $this->phpTag . "if ( ! $expression): ?>";
    }

    /**
     * Compile the User statements into valid PHP.
     *
     * @return string
     */
    protected function compileUser(): string
    {
        return $this->phpTagEcho . "'" . $this->currentUser . "'; ?>";
    }

    /**
     * Compile the endunless statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndunless(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the else statements into valid PHP.
     *
     * @return string
     */
    protected function compileElse(): string
    {
        return $this->phpTag . 'else: ?>';
    }

    /**
     * Compile the end-if statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndif(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileIsset($expression): string
    {
        return $this->phpTag . "if(isset{$expression}): ?>";
    }

    /**
     * @return string
     */
    protected function compileEndIsset(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * @return string
     */
    protected function compileEndSwitch(): string
    {
        --$this->switchCount;
        if ($this->switchCount < 0) {
            return $this->showError('@endswitch', 'Missing @switch', true);
        }
        return $this->phpTag . '} // end switch ?>';
    }

    /**
     * default tag used for switch/case
     *
     * @return string
     */
    protected function compileDefault(): string
    {
        if ($this->firstCaseInSwitch) {
            return $this->showError('@default', '@switch without any @case', true);
        }
        return $this->phpTag . 'default: ?>';
    }

    /**
     * Execute the case tag.
     *
     * @param $expression
     * @return string
     */
    protected function compileCase($expression): string
    {
        if ($this->firstCaseInSwitch) {
            $this->firstCaseInSwitch = false;
            return 'case ' . $expression . ': ?>';
        }
        return $this->phpTag . "case $expression: ?>";
    }

    /**
     * @param $expression
     * @return string
     */
    protected function compileSwitch($expression): string
    {
        $this->switchCount++;
        $this->firstCaseInSwitch = true;
        return $this->phpTag . "switch $expression {";
    }
}