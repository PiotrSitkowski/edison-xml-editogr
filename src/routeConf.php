<?php

use Routing\RouteCollection;

class routeConf
{

    public static function createCollection() : RouteCollection
    {

        $routes = new RouteCollection();

        $routes->add(
                '/',
                new Routing\Route('/', '\\Controller\\Home\initController::homeAction')
            );

        $routes->add(
                'invoices/search',
                new Routing\Route('invoices/search', '\\Controller\\Invoices\\invoicesController::searchAction')
            );

        $routes->add(
                'invoices/list',
                new Routing\Route('invoices/list', '\\Controller\\Invoices\\invoicesController::listAllFilesAction')
            );

        $routes->add(
                'invoices/edit/{xmlFileName}',
                new Routing\Route('invoices/edit/{xmlFileName}', '\\Controller\\Invoices\\invoicesController::editInvoiceAction', ['xmlFileName'=>'([a-zA-Z0-9\/\.\_]+)'])
            );

        $routes->add(
                'invoices/save',
                new Routing\Route('invoices/save', '\\Controller\\Invoices\\invoicesController::saveInvoiceAction')
            );

        return $routes;

    }
}