<?php
namespace Routing;

use Helper\Helper;
use Http\Request;
use Routing\RouteCollection;

/**
 * Matcher
 */
class Matcher
{

    private $request;
    private $routingCollection;

    function __construct(Request $request, RouteCollection $routing)
    {

        $this->request = $request;
        $this->routingCollection = $routing;

    }


    public function matchRequest()
    {
        $pathURI = $this->request->getPathInfo();
        if (empty($pathURI)) $pathURI = '/';

        foreach ($this->routingCollection->getCollection() as $path => $routing) {

            $attributes = $routing->getAttributes() ?? [];
            $keyAttribs = array_keys($attributes);
            $valAttribs = $attributes;

            $params = [];
            foreach ($keyAttribs as $key) {
                $params['{' . $key . '}'] = $valAttribs[$key];
            }

            # changing attributes to specific reqular expressions
            $pattern = '/'.ltrim(str_replace(array_keys($params), $params, $path),'/');
            $pattern = preg_replace('/{\w+}/', '.*', $pattern);

            # checking matching pattern
            preg_match('#^'.$pattern.'$#', $pathURI, $foundPattern);

            if ($foundPattern) {

                $this->request->setRoute($routing);

                $exp = explode('/',$pathURI);
                if (isset($exp[0]) && empty($exp[0])) array_splice($exp,0,1);

                $cntUriElem = count($exp);
                if (0 == $cntUriElem) return;

                $cntAttribs = count($attributes);

                # removing first params from URI elements for compare params to attributes pattern
                if ($cntUriElem > $cntAttribs) {
                    $spliceCnt = $cntUriElem - $cntAttribs;
                    array_splice($exp,0,$spliceCnt);
                }

                $res = [];
                foreach ($exp as $param) {
                    foreach ($attributes as $key => $pattern) {
                        if (preg_match('#^'.$pattern.'$#', $param)) {

                            if (!isset($res[$key])) {
                                $res[$key] = $param;
                                $this->request->setQuery($key, $param);
                                $this->request->setElement($key, $param);
                                break;
                            }
                        }
                    }
                }


                // Helper::debug( $res, true);

                // Helper::debug($routing, false);

                // Helper::debug('res: '.$pattern, false);
                // Helper::debug($pathURI, false);
            }

        }

    }

}