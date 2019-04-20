<?php
/**
 * Singleton class to store the application routes
 * @author: Leonardo Otoni
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
            //
            "login" => ["classes/controllers/public/LoginController.php"],
            "logout" => ["classes/controllers/public/LogoutController.php"],
            "signup" => ["classes/controllers/public/SignUpController.php"],
            "changepasswd" => ["classes/controllers/ChangeUserPasswordController.php", []],
            "changeemail" => ["classes/controllers/ChangeUserEmailController.php", []],
            "home" => ["classes/controllers/HomeController.php", []],

            //routes for patients
            "patientprofile" => ["classes/controllers/UserPatientController.php"],
            "appointment" => ["classes/controllers/MustDefineOne.php", []],
            "cancelappointment" => ["classes/controllers/MustDefineOne.php", []],
            "seeprescriptions" => ["classes/controllers/MustDefineOne.php", []],

            //routes for doctors
            "myschedule" => ["classes/controllers/DoctorScheduleController.php", [ISecurityProfile::DOCTOR]],
            "appointmentdetails" => ["classes/controllers/AppointmentDetailController.php", [ISecurityProfile::DOCTOR]],
            "teste3" => ["classes/controllers/MustDefineOne.php", [ISecurityProfile::DOCTOR]],
            "SetSchedule" =>["classes/controllers/SetScheduleController.php", [ISecurityProfile::DOCTOR]],

            "schedule" =>["classes/controllers/ScheduleController.php", [ISecurityProfile::DOCTOR]],
            "update" =>["classes/controllers/updateController.php", [ISecurityProfile::DOCTOR]],


            "changeappointment"=>["classes/controllers/ChangeAppointmentDetailController.php", [ISecurityProfile::DOCTOR]],
            "doctor-profile" =>["classes/controllers/DoctorProfileController.php", [ISecurityProfile::DOCTOR]],
            "treatpatient" =>["classes/controllers/TreatPatientController.php", [ISecurityProfile::DOCTOR]],
            "medicalhistory" =>["classes/controllers/MedicalHistoryController.php", [ISecurityProfile::DOCTOR]],


            //routes for Administration
            "searchuser" => ["classes/controllers/UserSearchController.php", [ISecurityProfile::SYSADMIN]],
            "setuserprofile" => ["classes/controllers/SetUserProfileController.php", [ISecurityProfile::SYSADMIN]],
            "medicalspecialty" => ["classes/controllers/MedicalSpecialtyController.php", [ISecurityProfile::SYSADMIN]],
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
