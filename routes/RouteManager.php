<?php
/**
 * Manages all routes registred in the routes Class and provide a controller to handle the request.
 * If a route is not found, a generic 404 controller is provided. Controllers located in the public
 * subfolder will not pass on the Security Filter.
 */
namespace routes {

    use \util\AppConstants as AppConstants;

    class RouteManager
    {

        private static $instance = null;
        private function __construct()
        {
        }

        public static function getInstance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new RouteManager();
            }
            return self::$instance;
        }

        //Get controller from the routes class.
        public function getControllerForRoute($route)
        {
            $controller = null;
            $registredRoutes = routes::getInstance()->getRoutes();

            /*Static resources like css and js also do requests.
             *For this cases, the controller will be the given route.*/
            if (array_key_exists($route, $registredRoutes)) {
                //route was found
                $controller = $registredRoutes[$route];
            } else {
                //route not registred in the controll, it will throw 404 :)
                $controller = AppConstants::_404_CONTROLLER;
            }

            return $controller;

        }

    }

}
