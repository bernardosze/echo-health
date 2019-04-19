<?php
/**
 * Filter file that is used for all incoming requests.
 * This filter rely on Apache .htaccess to work.
 *
 * @author: Leonardo Otoni
 */

define("ROOT_PATH", dirname(__FILE__, 1) . "/");
//require_once ROOT_PATH . "classes/util/ClassLoader.php";
require_once 'vendor/autoload.php';

use classes\util\AppConstants as AppConstants;
use \classes\util\AuthorizationFilter as AuthorizationFilter;
use \classes\util\helpers\Application as Application;
use \classes\util\MenuManager as MenuManager;
use \classes\util\SecurityFilter as SecurityFilter;
use \routes\RoutesManager as RoutesManager;

date_default_timezone_set(Application::getSetupConfig(Application::DEFAULT_TIME_ZONE));

$requestURI = removeModuleNameFromRoute($_SERVER['REQUEST_URI']);
$route = removeQueryString($requestURI);
dispatchRoute($route);

/**
 * Sanitize the module name got from $_SERVER[] removing the app name.
 */
function removeModuleNameFromRoute($requestURI)
{
    $moduleName = Application::getSetupConfig(Application::MODULE_NAME);
    $requestURI = str_replace($moduleName, "", $requestURI);
    return (!empty($requestURI) ? $requestURI : Application::getSetupConfig(Application::HOME_PAGE));
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
    $controller = RoutesManager::getControllerForRoute($route);
    //$test = \strpos($controller, constants::PUBLIC_CONTROLLERS);
    if ((\strpos($controller, AppConstants::PUBLIC_CONTROLLERS) === false)) {

        /* The controller is not public.
         * Apply the Security and Authorization Filters.
         * On case of user not logged, not authorized, or having an invalid
         * session will throw an automatic redirection to the login page or a 403 error.
         */
        SecurityFilter::getInstance()->validateUserSession();
        AuthorizationFilter::validateUserAuthorization($route);

        //TODO:: Remove this declaration from here as soon all controllers are inherinting from AppBaseController.
        //Filter the User Menu considering the profiles defined for the routes
        $appMenu = MenuManager::getFiltredMenus();
    }

    //The controller file must exists
    if (file_exists(ROOT_PATH . $controller)) {
        require_once ROOT_PATH . $controller;
    } else {
        require_once ROOT_PATH . RoutesManager::_404_CONTROLLER;
    }

}
