<?php
/**
 * Controller to handle user change password requests.
 * Author: Leonardo Otoni
 */
namespace classes\controllers\changePassword {

    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \routes\RoutesManager as RoutesManager;

    //set basic data to be used by the page
    $pageTitle = "User Password Change";
    $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
    $userEmail = $userSessionData->getEmail();
    $extraJS = [
        "static/js/sha1.min.js",
        "static/js/security.js",
        "static/js/validation/user_password_change.js",
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $userModel = new UserModel();
        $userModel->setId($userSessionData->getUserId());
        $userModel->setEmail($userEmail);
        $userModel->setPassword(filter_input(INPUT_POST, "currentPassword", FILTER_SANITIZE_STRING));
        $userModel->setNewPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

        try {
            $userBO = new UserBO();
            $userBO->updateUserPassword($userModel);
            $alertSuccessMessage = "Password successfully updated.";
        } catch (UpdateUserDataException $e) {
            //Set the error message to appear on screen
            $alertErrorMessage = $e->getMessage();
        } catch (\Exception $e) {
            require_once RoutesManager::_500_CONTROLLER;
            exit();
        }

    }

    require_once "views/templates/header.html";
    require_once "views/change_password.html";
    require_once "views/templates/footer.html";

}
