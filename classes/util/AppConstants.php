<?php
/**
 * GLOBAL APP CONSTANTS
 *
 * @author: Leonardo Otoni
 */

namespace classes\util {

    final class AppConstants
    {

        //Defines the module name. It must start and end with /
        //public const MODULE_NAME = "/echo-health/";
        public const MODULE_NAME = "/~bernardosze/humber-college/ite-5330/echo-health/";

        //Default App Home page
        public const HOME_PAGE = "login";

        public const HOME_PAGE_INTRANET = "home";

        //Default TimeZone - It will reflect when working with date / dateTime objects
        public const DEFAULT_TIME_ZONE = "America/Toronto";

        //Max login attempts before block a user
        public const MAX_LOGIN_ATTEMPS = 3;

        //Default login page address
        public const LOGIN_PAGE = self::MODULE_NAME . "login";

        //The session lifespan limit in seconds. Default 300 seconds (5 min).
        public const SESSION_DURATION_IN_SECONDS = 300;

        //User authenticated data [id, email]
        public const USER_SESSION_DATA = "USER_SESSION_DATA";

        //Used to save the time of user's last activity
        public const USER_LAST_ACTIVITY_TIME = "USER_LAST_ACTIVITY_TIME";

        public const USER_REGISTRATION_ERROR = "USER_REGISTRATION_ERROR";

        //General Error Messages
        public const USER_AUTHENTICATION_ERROR_MSG = "Invalid email or password.";

        //Controllers that not require session validation
        public const PUBLIC_CONTROLLERS = "controllers/public/";

        //Static Content does not require security
        public const STATIC_CONTENT = "static/";

        public const INVALID_SESSION_JSON = ["status" => "Invalid_Session"];

        public const DB_DSN_KEY = "DB_HOST";
        public const DB_PASSWORD_KEY = "DB_PASSWORD";
        public const DB_USERNAME_KEY = "DB_USERNAME";

    }

}
