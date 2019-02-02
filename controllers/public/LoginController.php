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

    const LOGIN_VIEW = "views/security/login.html";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        require_once ROOT_PATH . LOGIN_VIEW;
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
        } catch (Exception $e) {
            $userAuthenticationErrorMsg = AppConstants::USER_AUTHENTICATION_ERROR_MSG;
        }

        require_once ROOT_PATH . LOGIN_VIEW;
    }

}
