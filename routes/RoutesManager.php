<?php
/**
 * Manages all routes registred in the routes Class and provide a controller to handle the request.
 * If a route is not found, a generic 404 controller is provided. Controllers located in the public
 * subfolder will not pass on the Security Filter.
 */
namespace routes {

    use \routes\ApplicationRoutes as ApplicationRoutes;

    final class RoutesManager extends ApplicationRoutes
    {

        //Get controller from the routes class.
        public static function getControllerForRoute($route)
        {
            $controller = null;
            $registredRoutes = parent::getRoutes();

            /*Static resources like css and js also do requests.
             *For this cases, the controller will be the given route.*/
            if (array_key_exists($route, $registredRoutes)) {
                //route was found
                $controller = $registredRoutes[$route][0];
            } else {
                //route not registred in the controll, it will throw 404 :)
                $controller = parent::_404_CONTROLLER;
            }

            return $controller;

        }

        /**
         * Returns all routes registered into the application
         */
        public static function getApplicationRoutes()
        {
            return parent::getRoutes();
        }

    }
}
