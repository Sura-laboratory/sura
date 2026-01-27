<?php

namespace Tests;

use Sura\View\View;

class CompilationTest extends AbstractBladeTestCase
{
    /**
     * @throws \Exception
     */
    public function testCompilation()
    {
        $this->assertEqualsIgnoringWhitespace("Compilation test template", $this->blade->run('compilation.base', []));
    }

    /**
     * @throws \Exception
     */
    /*    public function testCompilationCallBack()
            {
                $this->blade->setMode(View::MODE_DEBUG);
                $this->blade->compileCallbacks[]= static function (&$content, $templatename = null) {
                    $content=strtoupper($content);
                };
                $this->blade->compileCallbacks[]= static function (&$content, $templatename = null) {
                    $content='**'.$content.'**';
                };

                $this->assertEqualsIgnoringWhitespace("**COMPILATIONTESTTEMPLATE**", $this->blade->run('compilation.base', []));
                $this->blade->compileCallbacks=[];
            }*/

    /**
     * @throws \Exception
     */
    public function testCompilationCreatesCompiledFile()
    {
        $this->blade->run('compilation.base', []);
        // we don't need to re-create the name manually, the function already exists.
        $this->assertFileExists($this->blade->getCompiledFile('compilation.base'));
    }

    /**
     * @throws \Exception
     */
    public function testCompilationDebugCreatesCompiledFile()
    {
        $this->blade->setMode(View::MODE_DEBUG);
        $this->blade->run('compilation.base', []);

        $this->assertFileExists(__DIR__ . '/resources/compiled/compilation.base.bladec');

        $this->blade->setMode(View::MODE_SLOW);
    }

    /**
     * @throws \Exception
     */
    /*    public function testCompilationCustomFileExtension()
        {
            $this->blade->setFileExtension('.blade.php');

            $this->assertEqualsIgnoringWhitespace("Custom extension blade file", $this->blade->run('compilation.base', []));

            $this->blade->setFileExtension('.blade.php');
        }*/

    /**
     * For the issue #57. Version 3.16
     * @throws \Exception
     */
    /*    public function testCompilationTemplateExist()
        {
            $this->blade->setFileExtension('.blade');

            $this->assertEquals(true, $this->blade->compile('compilation.base'), "Running compile method");

            $this->blade->setFileExtension('.blade.php');
        }*/


    /**
     * @throws \Exception
     */
    public function testCompilationCustomCompileExtension()
    {
        $this->blade->setCompiledExtension('.bladeD');
        $this->blade->run('compilation.base', []);

        $this->assertFileExists(__DIR__ . '/resources/compiled/' . sha1('compilation.base') . '.bladeD');

        $this->blade->setCompiledExtension('.bladec');
    }
}
