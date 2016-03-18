<?php
use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Response;

class PlatesRendererTest extends PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $renderer = new \Cwchentw\PlatesRenderer("tests/");

        $headers = new Headers();
        $body = new Body(fopen('php://temp', 'r+'));
        $response = new Response(200, $headers, $body);

        $newResponse = $renderer->render($response, "testTemplate.php",
                                         array("message" => "Hello"));
        $newResponse->getBody()->rewind();

        $output = <<<HTML
<!DOCTYPE html>
<html>
<body>
<p>Hello</p>
</body>
</html>
HTML;
        $this->assertEquals($output, $newResponse->getBody()->getContents());
    }

    public function testAttributeMerging()
    {
        $renderer = new \Cwchentw\PlatesRenderer("tests/", [
            "hello" => "Hello"
        ]);
        $headers = new Headers();
        $body = new Body(fopen('php://temp', 'r+'));
        $response = new Response(200, $headers, $body);
        $newResponse = $renderer->render($response, "template.php", [
            "hello" => "Hi"
        ]);
        $newResponse->getBody()->rewind();
        $this->assertEquals("Hi", $newResponse->getBody()->getContents());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionForTemplateInData() {
        $renderer = new \Cwchentw\PlatesRenderer("tests/");

        $headers = new Headers();
        $body = new Body(fopen('php://temp', 'r+'));
        $response = new Response(200, $headers, $body);

        $renderer->render($response, "testTemplate", [
            "template" => "Hi"
        ]);
    }

    /**
     * @expectedException LogicException
     */
    public function testTemplateNotFound()
    {
        $renderer = new \Cwchentw\PlatesRenderer('tests/');

        $headers = new Headers();
        $body = new Body(fopen('php://temp', 'r+'));
        $response = new Response(200, $headers, $body);

        $renderer->render($response, "non-exist-template", []);
    }
}