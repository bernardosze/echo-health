<?php

namespace util {

    use \routes\RoutesManager as RoutesManager;
    use \util\AppConstants as AppConstants;
    use \util\interfaces\ISecurityProfile as ISecurityProfile;

    /**
     * Class to check the the user authorization for a given route.
     *
     * Author: Leonardo Otoni
     */
    final class AuthorizationFilter
    {

        private function __construct()
        {
        }

        /**
         * For a given route, checks whether the Current user is authorized or not.
         */
        public static function isUserAuthorized($route)
        {
            $authorized = false;

            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $userProfiles = $userSessionProfile->getProfiles();

            if (\in_array(ISecurityProfile::SYSADMIN, $userProfiles)) {
                //Sysadmin is fully authorized into the application
                $authorized = true;
            } else {

                $appRoutesData = RoutesManager::getApplicationRoutes();
                $routeData = $appRoutesData[$route];

                if (\count($routeData) > 1) {

                    //appRouteData[1] contains an array of profile permissions
                    foreach ($userProfiles as $profile) {
                        if (\in_array($profile, $routeData[1])) {
                            $authorized = true;
                            break;
                        }
                    }
                }

            }

            return $authorized;

        }

        /**
         * Performs the user authorization check for a given route.
         * If the User is not authorized, the Filter will dispatch an http 403 error
         */
        public static function validateUserAuthorization($route)
        {
            if (!self::isUserAuthorized($route)) {
                $controller = RoutesManager::_403_CONTROLLER;
                require_once $controller;
            }
        }

    }

}
