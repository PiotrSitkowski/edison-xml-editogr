<?php

use Helper\Helper;
use Http\Request;
use Http\Response;
use Routing\Matcher;
use Routing\RouteCollection;
use Routing\ControllerResolver;

/**
 * Framework
 * Author: Piotr Sitkowski
 */
class Framework
{

    private $request;
    private $routes;
    private $rootDIR = __DIR__ . '/..';

    const debug = true;
    const caching = false;

    function __construct()
    {
        config::getInstance(); // init configuration data - singleton

        $this->routes = routeConf::createCollection(); // init route collection
    }


    public function handle(Request $request) : Response
    {
        $this->request = $request;

        $matcher = new Matcher($this->request, $this->routes);
        $matcher->matchRequest();

        $controller = new ControllerResolver($this->request, $this->routes);
        return $controller->call();
    }


    public function render(string $templateName, array $contentData = array()) : string
    {

        $tmpl = new Smarty();
        $tmpl->debugging = self::debug;
        $tmpl->caching = self::caching;

        $templateDir = $this->rootDIR . '/templates';

        $tmpl->setTemplateDir($templateDir)->
            setCompileDir($this->rootDIR . '/var/cache/templates_c')->
            setCacheDir($this->rootDIR . '/var/cache');


        if (count($contentData)) {
            foreach ($contentData as $key => $value) {
                $tmpl->assign($key, $value);
            }
        }


        return $tmpl->fetch($templateDir . '/' . $templateName);

    }

}