<?php
namespace Routing;


/**
 * Route
 */
class Route
{

    private $pathURI = null;
    private $controller = null;
    private $attributes = null;

    function __construct(string $pathURI, string $controller, array $attributes = [])
    {
        $this->pathURI = $pathURI;
        $this->controller = $controller;
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes ?? null;
    }


    public function getControllerClass()
    {
        return $this->controller ?? null;
    }

}