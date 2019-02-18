<?php
/**
 * Singleton class to store the application routes
 * Author: Leonardo Otoni
 */
namespace routes {

    use \util\interfaces\ISecurityProfile as ISecurityProfile;

    class ApplicationRoutes implements ISecurityProfile
    {

        /**
         * All application routes must be defined here
         * "route" => ["controller class", [authorized profiles] ]
         * controllers in the public/ folder will not require security
         */
        private static $routes = [
            "login" => ["controllers/public/LoginController.php"],
            "logout" => ["controllers/public/LogoutController.php"],
            "signup" => ["controllers/public/SignUpController.php"],
            "changepasswd" => ["controllers/ChangeUserPasswordController.php", [ISecurityProfile::PATIENT]],
            "home" => ["controllers/HomeController.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for patients
            "appointment" => ["controllers/MustDefineOne.php", []],
            "cancelappointment" => ["controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "seeprescriptions" => ["controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for doctors
            "myschedule" => ["controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "test2" => ["controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],
            "seeprescriptions" => ["controllers/MustDefineOne.php", [ISecurityProfile::PATIENT, ISecurityProfile::DOCTOR]],

            //routes for Administration
            "admin/setdoctor" => ["controllers/setdoctor.php", [ISecurityProfile::SYSADMIN]],
            "admin/setdoctorschedule" => ["controllers/setdoctorschedule.php", [ISecurityProfile::SYSADMIN]],
            "admin/setworkingdays" => ["controllers/setworkingdays.php", [ISecurityProfile::SYSADMIN]],

        ];

        //Default http error handlers
        public const _403_CONTROLLER = "controllers/public/_403Controller.php";
        public const _404_CONTROLLER = "controllers/public/_404Controller.php";
        public const _500_CONTROLLER = "controllers/public/_500Controller.php";

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
