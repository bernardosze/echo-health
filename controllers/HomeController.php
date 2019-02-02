<?php
/**
 * App Home Page Controller
 */

namespace controller {

    use \util\AppConstants as AppConstants;

    $userData = $_SESSION[AppConstants::USER_SESSION_DATA];
    $firstName = $userData["firstName"];

    require_once "views/home.html";

}
