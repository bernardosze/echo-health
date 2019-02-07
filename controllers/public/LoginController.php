<?php
/**
 * Default app login controller.
 *
 * Author: Leonardo Otoni
 */
namespace controllers\publicControllers {

    use Exception;
    use \models\UserModel as UserModel;
    use \util\AppConstants as AppConstants;
    use \util\exceptions\AuthenticationException as AuthenticationException;
    use \util\SecurityFilter as SecurityFilter;

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
        $email = filter_input(INPUT_POST, 'email');
        $hash = filter_input(INPUT_POST, 'hash');

        try {
            $userModel = new UserModel();
            $userData = $userModel->authenticateUser($email, $hash);
            session_start();
            $_SESSION[AppConstants::USER_SESSION_DATA] = $userData;
            $_SESSION[AppConstants::USER_LAST_ACTIVITY_TIME] = $_SERVER["REQUEST_TIME"];
            header("Location: " . AppConstants::HOME_PAGE_INTRANET);
        } catch (AuthenticationException $e) {
            //User could not be authenticated
            $userAuthenticationErrorMsg = AppConstants::USER_AUTHENTICATION_ERROR_MSG;
        } catch (Exception $e) {
            //Exception not expected from model
            require_once "_500Controller.php";
            exit();
        }

        require_once ROOT_PATH . LOGIN_VIEW;
    }

}
