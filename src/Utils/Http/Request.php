<?php
namespace Http;

use Routing\Route;

/**
 * Request
 */
class Request
{

    private $query = null;
    private $elements = [];
    private $request = null;
    private $server = null;

    function __construct()
    {
        $this->registerFromGlobals($_GET, $_POST, $_SERVER);
        // \Helper\Helper::debug($_POST);
    }


    private function registerFromGlobals(array $query = [], array $request = [], array $server = [])
    {
        $this->query = $query;
        $this->request = $request;
        $this->server = $server;
    }

    public function setQuery(string $key, string $value)
    {
        $this->query[$key] = $value;
        $_GET[$key] = $value;
    }

    public function getQuery(string $queryName) : ?string
    {
        return $this->query[$queryName] ?? null;
    }

    public function setElement(string $key, string $value)
    {
        $this->elements[$key] = $value;
    }

    public function getElement(string $key) : ?string
    {
        return $this->elements[$key] ?? null;
    }

    public function getAllElements() : array
    {
        return $this->elements ?? [];
    }

    public function getRequest(?string $requestName = null)
    {
        return !is_null($requestName)
            ? ($this->request[$requestName] ?? null)
            : $this->request;
    }

    public function getServer() : array
    {
        return $this->server ?? [];
    }

    public function getPathInfo() : string
    {
        return $this->server['PATH_INFO'] ?? '';
    }


    public function setRoute(Route $routing)
    {
        $this->route = $routing;
    }

    public function getRoute()
    {
        return $this->route;
    }


}