<?php
namespace Routing;

use Framework;
use Http\Request;
use Http\Response;
use Helper\Helper;

/**
 * ControllerResolver
 */
class ControllerResolver extends Framework
{
    private $pathURI;
    private $routing;
    private $arguments = [];
    private $controller = null;
    private $method = null;
    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->pathURI = $request->getPathInfo();
        $this->routing = $request->getRoute();
        $this->arguments = $request->getAllElements();

    }

    public function call()
    {
        $requestURI = !empty($this->pathURI) ? $this->pathURI : '/';

        if (!is_object($this->routing)) return new Response($this->render('404.tpl'), 404);

        $controllerMethod = $this->routing->getControllerClass() ?? 'null';
        list($class, $method) = explode('::', $controllerMethod, 2);

        // if (empty($method)) $method = '__construct';

        if (class_exists($class)) {

            $instance = new $class($this->request);

            if (method_exists($instance, $method)) {

                $this->controller = $instance;
                $this->method = $method;

                return call_user_func_array([$this->controller, $this->method], $this->arguments);

            } else {

                return new Response($this->render('404.tpl'), 404);
            }
        } else {
            return new Response($this->render('404.tpl'), 404);
        }

        return new Response();
    }



}