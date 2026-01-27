# Blade Template Engine

Sura\View is a standalone version of Blade Template Engine that uses a single PHP file and can be ported and used in
different projects. It allows you to use blade template outside Laravel.

## Usage

If you use **composer**, then you could add the library using the next command (command line)

> composer require Sura/view

If you don't use it, then you could download the library and include it manually.

### Implicit definition

```php
use Sura\View\View;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new View($views,$cache,View::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.
echo $blade->run("hello",array("variable1"=>"value1")); // it calls /views/hello.blade.php
```

Where `$views` is the folder where the views (templates not compiled) will be stored.
`$cache` is the folder where the compiled files will be stored.

In this example, the BladeOne opens the template **hello**. So in the views folder it should exist a file called **
hello.blade.php**

views/hello.blade.php:

```html
<h1>Title</h1>
{{$variable1}}
```
