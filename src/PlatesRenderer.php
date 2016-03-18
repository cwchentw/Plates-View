<?php
namespace Cwchentw;

require './vendor/autoload.php';

use \InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class PlatesRenderer
{
    protected $templatePath;

    protected $attributes;

    protected $engine;

    public function __construct($templatePath="", $attributes=[])
    {
        $this->setTemplatePath($templatePath);
        $this->setAttributes($attributes);
        $this->initEngine($templatePath);
    }

    public function render(ResponseInterface $response, $template, array $data=[])
    {
        $output = $this->fetch($template, $data);

        $response->getBody()->write($output);

        return $response;
    }

    public function fetch($template, array $data = [])
    {
        if (isset($data['template'])) {
            throw new \InvalidArgumentException("Duplicate template key found");
        }

        /*if (!is_file($this->templatePath . $template)) {
            throw new \RuntimeException("View cannot render `$template` because the template does not exist");
        }*/

        $data = array_merge($this->attributes, $data);

        # Set the file extension of the engine
        $ext = $ext = pathinfo($template, PATHINFO_EXTENSION);
        $this->engine->setFileExtension($ext);

        # Get the filename without extension
        $filename = pathinfo($template, PATHINFO_FILENAME);

        # Plates will automatically add file extension
        # Therefore, we remove the extension of our file.
        $output = $this->engine->render($filename, $data);

        return $output;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function addAttributes($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key)
    {
        if (!isset($this->attributes[$key])) {
            return false;
        }

        return $this->attributes[$key];
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }

    protected function initEngine($path)
    {
        $this->engine = new \League\Plates\Engine($path);
    }
}