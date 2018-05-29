<?php
namespace Routing;

use Routing\Route;

/**
 * Route Collection
 */
class RouteCollection
{
    private $collection = [];

    function __construct()
    {
    }

    public function add(string $name, Route $route)
    {
        unset($this->collection[$name]);
        $this->collection[$name] = $route;
    }


    public function getCollection()
    {
        return $this->collection;
    }

}