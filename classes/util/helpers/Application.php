<?php

namespace classes\util\helpers;

/**
 * Helper class that provide access to the app.ini file
 */
final class Application
{

    //Defines the module name. It must start and end with /
    public const MODULE_NAME = "MODULE_NAME";

    //Default login page address
    public const LOGIN_PAGE = "LOGIN_PAGE";

    //Default App Home page
    public const HOME_PAGE = "HOME_PAGE";

    public const HOME_PAGE_INTRANET = "HOME_PAGE_INTRANET";

    //Default TimeZone - It will reflect when working with date / dateTime objects
    public const DEFAULT_TIME_ZONE = "DEFAULT_TIME_ZONE";

    //Max login attempts before block a user
    public const MAX_LOGIN_ATTEMPS = "MAX_LOGIN_ATTEMPS";

    //The session lifespan limit in seconds. Default 300 seconds (5 min).
    public const SESSION_DURATION_IN_SECONDS = "SESSION_DURATION_IN_SECONDS";

    //Database properties
    public const DB_DSN = "DB_HOST";
    public const DB_PASSWORD = "DB_PASSWORD";
    public const DB_USERNAME = "DB_USERNAME";

    /**
     * Return an Application Configuration by a provided key.
     * @param Application Setup Key
     */
    public static function getSetupConfig(string $setupKey)
    {
        $appIni = parse_ini_file("./app.ini");
        return $appIni[$setupKey];
    }
}
