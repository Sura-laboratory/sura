<?php


namespace Sura\View\Compilers\Concerns;


trait CompilesLoops
{
    /**
     * Compile the forelse statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileForelse(string $expression): string
    {
        $empty = '$__empty_' . ++$this->forelseCounter;
        return $this->phpTag . "{$empty} = true; foreach{$expression}: {$empty} = false; ?>";
    }

    /**
     * Compile the if statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileIf(string $expression): string
    {
        return $this->phpTag . "if{$expression}: ?>";
    }

    /**
     * Compile the else-if statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileElseif(string $expression): string
    {
        return $this->phpTag . "elseif{$expression}: ?>";
    }

    /**
     * Compile the forelse statements into valid PHP.
     *
     * @param string $expression empty if it's inside a for loop.
     * @return string
     */
    protected function compileEmpty($expression = ''): string
    {
        if ($expression == '') {
            $empty = '$__empty_' . $this->forelseCounter--;
            return $this->phpTag . "endforeach; if ({$empty}): ?>";
        }
        return $this->phpTag . "if (empty{$expression}): ?>";
    }

    /**
     * Compile the has section statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileHasSection(string $expression): string
    {
        return $this->phpTag . "if (! empty(trim(\$this->yieldContent{$expression}))): ?>";
    }

    /**
     * Compile the end-while statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndwhile(): string
    {
        return $this->phpTag . 'endwhile; ?>';
    }

    /**
     * Compile the end-for statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndfor(): string
    {
        return $this->phpTag . 'endfor; ?>';
    }

    /**
     * Compile the end-for-each statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndforeach(): string
    {
        return $this->phpTag . 'endforeach; $this->popLoop(); $loop = $this->getFirstLoop(); ?>';
    }

    /**
     * Compile the for statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileFor(string $expression): string
    {
        return $this->phpTag . "for{$expression}: ?>";
    }

    /**
     * Compile the foreach statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileForeach(string $expression): string
    {
        //\preg_match('/\( *(.*) * as *([^\)]*)/', $expression, $matches);
        \preg_match('/\( *(.*) * as *([^)]*)/', $expression, $matches);
        $iteratee = \trim($matches[1]);
        $iteration = \trim($matches[2]);
        $initLoop = "\$__currentLoopData = {$iteratee}; \$this->addLoop(\$__currentLoopData);\$this->getFirstLoop();\n";
        $iterateLoop = '$loop = $this->incrementLoopIndices(); ';
        return $this->phpTag . "{$initLoop} foreach(\$__currentLoopData as {$iteration}): {$iterateLoop} ?>";
    }

    /**
     * Compile a split of a foreach cycle. Used for example when we want to separate limites each "n" elements.
     *
     * @param string $expression
     * @return string
     */
    protected function compileSplitForeach(string $expression): string
    {
        return $this->phpTagEcho . '$this::splitForeach' . $expression . '; ?>';
    }

    /**
     * Compile the break statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileBreak(string $expression): string
    {
        return $expression ? $this->phpTag . "if{$expression} break; ?>" : $this->phpTag . 'break; ?>';
    }

    /**
     * Compile the continue statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileContinue(string $expression): string
    {
        return $expression ? $this->phpTag . "if{$expression} continue; ?>" : $this->phpTag . 'continue; ?>';
    }

    /**
     * Compile the auth statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileAuth($expression = ''): string
    {
        $role = $this->stripParentheses($expression);
        if ($role == '') {
            return $this->phpTag . 'if(isset($this->currentUser)): ?>';
        }

        return $this->phpTag . "if(isset(\$this->currentUser) && \$this->currentRole=={$role}): ?>";
    }

    /**
     * Compile the elseauth statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileElseAuth(mixed $expression = ''): string
    {
        $role = $this->stripParentheses($expression);
        if ($role == '') {
            return $this->phpTag . 'else: ?>';
        }

        return $this->phpTag . "elseif(isset(\$this->currentUser) && \$this->currentRole=={$role}): ?>";
    }

    /**
     * Compile the end-for-else statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndforelse(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * @return string
     */
    protected function compileEndEmpty(): string
    {
        return $this->phpTag . 'endif; ?>';
    }

    /**
     * Compile the while statements into valid PHP.
     *
     * @param string $expression
     * @return string
     */
    protected function compileWhile(string $expression): string
    {
        return $this->phpTag . "while{$expression}: ?>";
    }
}