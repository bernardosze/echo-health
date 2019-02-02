<?php
/**
 * Singleton class to store the application routes
 * Author: Leonardo Otoni
 */
namespace routes {

    class Routes
    {
        /**
         * All application routes must be defined here
         * "route" => "controller class"
         * controllers in the public/ folder will not require security
         */
        private static $routes = array(
            "login" => "controllers/public/LoginController.php",
            "logout" => "controllers/public/LogoutController.php",
            "signup" => "controllers/public/SignUpController.php",
            "home" => "controllers/HomeController.php",
        );

        /**
         * Routes for static resourses that will not require security
         */
        private static $staticResourcesRoutes = array(
            "static/css/",
            "static/img/",
            "static/js/",
        );

        private static $instance = null;
        private function construct()
        {
        }

        public static function getInstance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new Routes();
            }
            return self::$instance;
        }

        public function getRoutes()
        {
            return self::$routes;
        }

        public function getRoutesStaticResources()
        {
            return self::$staticResourcesRoutes;
        }

    }

}
