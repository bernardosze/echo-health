<?php
/**
 * GLOBAL APP CONSTANTS
 *
 * @author: Leonardo Otoni
 */

namespace classes\util {

    final class AppConstants
    {

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

    }

}
