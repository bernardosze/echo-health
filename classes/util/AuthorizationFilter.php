<?php

namespace classes\util {

    use \classes\util\AppConstants as AppConstants;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;
    use \routes\RoutesManager as RoutesManager;

    /**
     * Class to check the the user authorization for a given route.
     *
     * @author: Leonardo Otoni
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
            if (!empty($userSessionProfile)) {

                $userProfiles = $userSessionProfile->getProfiles();

                if (\in_array(ISecurityProfile::SYSADMIN, $userProfiles)) {
                    //Sysadmin is fully authorized into the application
                    $authorized = true;
                } else {

                    $appRoutesData = RoutesManager::getApplicationRoutes();
                    $routeData = $appRoutesData[$route];

                    if (\count($routeData) > 1 && \count($routeData[1]) > 0) {

                        //appRouteData[1] contains an array of profile permissions
                        foreach ($userProfiles as $profile) {
                            if (\in_array($profile, $routeData[1])) {
                                $authorized = true;
                                break;
                            }
                        }
                    } else {
                        //Route does not require authorization
                        $authorized = true;
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
