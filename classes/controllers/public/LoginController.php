<?php
/**
 * Default app login controller.
 *
 * Author: Leonardo Otoni
 */
namespace classes\controllers\publicControllers {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\AuthenticationException as AuthenticationException;
    use \classes\util\SecurityFilter as SecurityFilter;
    use \routes\RoutesManager as RoutesManager;

    const LOGIN_VIEW = "views/security/login.html";
    $email = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $securityFilter = SecurityFilter::getInstance();
        if ($securityFilter->isUserLogged() && !$securityFilter->isExpiredSession()) {
            //User is already authenticated, so dispatch to the intranet home.
            header("Location: " . AppConstants::HOME_PAGE_INTRANET);
        } else {
            require_once ROOT_PATH . LOGIN_VIEW;
        }

    } else {
        //the user login form was posted
        $userModel = new UserModel();
        $userModel->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
        $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

        try {
            $userBO = new UserBO();
            $userSessionData = $userBO->authenticateUser($userModel);
            session_start();
            $_SESSION[AppConstants::USER_SESSION_DATA] = serialize($userSessionData);
            $_SESSION[AppConstants::USER_LAST_ACTIVITY_TIME] = $_SERVER["REQUEST_TIME"];
            header("Location: " . AppConstants::HOME_PAGE_INTRANET);
        } catch (AuthenticationException $e) {
            //User could not be authenticated
            $userAuthenticationErrorMsg = AppConstants::USER_AUTHENTICATION_ERROR_MSG;
        } catch (Exception $e) {
            //Exception not expected from model
            require_once RoutesManager::_500_CONTROLLER;
            exit();
        }

        require_once ROOT_PATH . LOGIN_VIEW;
    }

}
