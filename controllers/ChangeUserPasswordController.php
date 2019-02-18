<?php
/**
 * Controller to handle user change password requests.
 * Author: Leonardo Otoni
 */
namespace controllers\changePassword {

    use \models\UserModel as UserModel;
    use \routes\RoutesManager as RoutesManager;
    use \util\AppConstants as AppConstants;
    use \util\exceptions\UpdateUserDataException as UpdateUserDataException;

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

        $userId = $userSessionData->getUserId();
        $hash = filter_input(INPUT_POST, 'hash');

        try {
            $userModel = new UserModel();
            $userModel->updateUserPassword($userId, $hash);
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
