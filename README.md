# PlatesRenderer

Slim Framework 3 view helper built on top of Plates templating engine

## Installation

Install with [Composer](http://getcomposer.org):

```
    $ composer require cwchentw/slim-plates-view
```

## Usage with Slim 3

```php
use Slim\Views\PlatesRenderer;

include "vendor/autoload.php";

$app = new Slim\App();

$container = $app->getContainer();
$container['renderer'] = new PlatesRenderer("./templates");

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->renderer->render($response, "/hello.php", $args);
});

$app->run();

```

## Template Variables

You can now add variables to your renderer that will be available to all templates you render.

```php
// via the constructor
$templateVariables = [
    "title" => "Title"
];
$phpView = new PlatesRenderer("./path/to/templates", $templateVariables);

// or setter
$phpView->setAttributes($templateVariables);

// or individually
$phpView->addAttribute($key, $value);
```

Data passed in via `->render()` takes precedence over attributes.
```php
$templateVariables = [
    "title" => "Title"
];
$phpView = new PlatesRenderer("./path/to/templates", $templateVariables);

//...

$phpView->render($response, $template, [
    "title" => "My Title"
]);
// In the view above, the $title will be "My Title" and not "Title"
```

## Exceptions
`\LogicException` - if template does not exist

`\InvalidArgumentException` - if $data contains 'template'
