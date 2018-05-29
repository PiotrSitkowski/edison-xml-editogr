<?php
namespace Controller\Home;

use Framework;
use Controller\Controller;
use Http\Request;
use Http\Response;

/**
 * initController
 */
class initController extends Controller
{
    private $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function homeAction()
    {

        return new Response(
                $this->render('index.tpl')
            );
    }
}