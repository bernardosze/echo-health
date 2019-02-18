<?php

namespace util {

    use \routes\RoutesManager as RoutesManager;
    use \util\AuthorizationFilter as AuthorizationFilter;
    use \util\interfaces\ISecurityProfile as ISecurityProfile;

    /**
     * Helper class to provide menu data according to the user profile
     * authenticated into the application.
     *
     * Author: Leonardo Otoni
     */
    final class MenuManager implements ISecurityProfile
    {
        /**
         * Application menu object. It must follow the format:
         * [ Main MenuItem_1 => [ [SubMenuItem => route],... ], ...]
         */
        private static $applicationMenu = [
            "Appointment" =>
            [
                "Do Appointment" => "appointment",
                "Cancel Appointment" => "cancelappointment",
                "See Prescriptions" => "seeprescriptions",
            ],
            "Attendance" =>
            [
                "See Schedule" => "myschedule",
                "Test 2" => "test2",
                "test 3" => "seeprescriptions",
            ],
            "Administration" =>
            [
                "Set Doctor" => "admin/setdoctor",
                "Set Doctor Schedule" => "admin/setdoctorschedule",
                "Set Working Days" => "admin/setworkingdays",
            ],

        ];

        /**
         * It returns all menu itens that an authenticated user has rights to access.
         * All permissons came from the routes record
         */
        public static function getFiltredMenus()
        {
            $filteredMenuForRoutes = [];

            //get all recorded application's routes
            $applicationRoutes = RoutesManager::getApplicationRoutes();

            /**
             * Traverse the menu object to filter it against authorization.
             * The subMenuRoute must be a registred route into the ApplicationRoutes record.
             * If a route exists, then check the authorization for the current user.
             */
            foreach (self::$applicationMenu as $menuItem => $subMenuItems) {
                foreach ($subMenuItems as $subMenuItem => $subMenuRoute) {
                    if (\array_key_exists($subMenuRoute, $applicationRoutes)) {
                        if (AuthorizationFilter::isUserAuthorized($subMenuRoute)) {
                            //User is allowed to see the menu Item
                            $filteredMenuForRoutes[$menuItem][$subMenuItem] = $subMenuRoute;
                        }
                    }
                }
            }

            return $filteredMenuForRoutes;
        }

    }
}
