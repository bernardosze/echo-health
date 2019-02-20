<?php
/**
 * Singleton class to store the application routes
 * Author: Leonardo Otoni
 */
namespace routes {

    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    class ApplicationRoutes implements ISecurityProfile
    {

        /**
         * All application routes must be defined here
         * "route" => ["controller class", [authorized profiles] ]
         * controllers in the public/ folder will not require security
         */
        private static $routes = [
            "login" => ["classes/controllers/public/LoginController.php"],
            "logout" => ["classes/controllers/public/LogoutController.php"],
            "signup" => ["classes/controllers/public/SignUpController.php"],
            "changepasswd" => ["classes/controllers/ChangeUserPasswordController.php", [ISecurityProfile::PATIENT]],
            "changeemail" => ["classes/controllers/ChangeUserEmailController.php", [ISecurityProfile::PATIENT]],
            "home" => ["classes/controllers/HomeController.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for patients
            "appointment" => ["classes/controllers/MustDefineOne.php", []],
            "cancelappointment" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "seeprescriptions" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for doctors
            "myschedule" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "test2" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "seeprescriptions" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for Administration
            "admin/setdoctor" => ["classes/controllers/setdoctor.php", [ISecurityProfile::SYSADMIN]],
            "admin/setdoctorschedule" => ["classes/controllers/setdoctorschedule.php", [ISecurityProfile::SYSADMIN]],
            "admin/setworkingdays" => ["classes/controllers/setworkingdays.php", [ISecurityProfile::SYSADMIN]],

        ];

        //Default http error handlers
        public const _403_CONTROLLER = "classes/controllers/public/_403Controller.php";
        public const _404_CONTROLLER = "classes/controllers/public/_404Controller.php";
        public const _500_CONTROLLER = "classes/controllers/public/_500Controller.php";

        //returns all routes registred
        protected static function getRoutes()
        {
            return self::$routes;
        }

        protected static function getRouteData($route)
        {
            if (array_key_exists(self::$routes, $route)) {
                return self::$routes[$route];
            } else {
                return null;
            }
        }

    }

}
