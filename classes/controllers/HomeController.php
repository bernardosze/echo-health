<?php
/**
 * App Home Page Controller
 * @author: Leonardo Otoni
 */

namespace classes\controllers {

    use \classes\util\AppConstants as AppConstants;

    $pageTitle = "Home Page";
    $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
    $firstName = $userSessionProfile->getFirstName();

    require_once "views/templates/header.html";
    require_once "views/home.html";
    require_once "views/templates/footer.html";

}
