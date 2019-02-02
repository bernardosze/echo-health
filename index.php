<?php
/**
 * Filter file that is used for all incoming requests.
 * This filter rely on Apache .htaccess to work.
 *
 * Author: Leonardo Otoni
 */

define("ROOT_PATH", dirname(__FILE__, 1) . "/");
require_once ROOT_PATH . "util/ClassLoader.php";

use \routes\RouteManager as RouteManager;
use \util\AppConstants as AppConstants;
use \util\SecurityFilter as SecurityFilter;

date_default_timezone_set(AppConstants::DEFAULT_TIME_ZONE);

$requestURI = removeModuleNameFromRoute($_SERVER['REQUEST_URI']);
$route = removeQueryString($requestURI);
dispatchRoute($route);

/**
 * Sanitize the module name got from $_SERVER[] removing the app name.
 */
function removeModuleNameFromRoute($requestURI)
{
    $moduleName = AppConstants::MODULE_NAME;
    $requestURI = str_replace($moduleName, "", $requestURI);
    return (!empty($requestURI) ? $requestURI : AppConstants::HOME_PAGE);
}

/**
 * Separetes the query string to find a route.
 */
function removeQueryString($requestURI)
{
    $route = explode("?", $requestURI);
    return $route[0];
}

/**
 * For a given route, it call the route manager controller and gets a controller to handle
 * the request.
 */
function dispatchRoute($route)
{
    $controller = RouteManager::getInstance()->getControllerForRoute($route);
    //$test = \strpos($controller, constants::PUBLIC_CONTROLLERS);
    if ((\strpos($controller, AppConstants::PUBLIC_CONTROLLERS) === false)) {
        /* The controller is not public. Apply the SecurityFilter
         * A redirect to login page will be done if the user not survive in the Filter. :)
         */
        SecurityFilter::getInstance()->validateUserSession();
    }

    require_once ROOT_PATH . "/" . $controller;

}
