<?php
/**
 * App Home Page Controller
 * Author: Leonardo Otoni
 */

namespace controllers {

    use \util\AppConstants as AppConstants;

    $pageTitle = "Home Page";
    $userData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
    $firstName = $userData->getFirstName();

    require_once "views/templates/header.html";
    require_once "views/home.html";
    require_once "views/templates/footer.html";

}
