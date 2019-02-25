<?php

/**
 * Controller to handle user email change requests
 * Author: Leonardo Otoni
 */
namespace classes\controllers\changeemail {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\UserSessionProfile as UserSessionProfile;

    $pageTitle = "User Email Change";
    $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
    $currentEmail = $userSessionData->getEmail();
    $newEmail = "";
    $extraJS = [
        "static/js/sha1.min.js",
        "static/js/security.js",
        "static/js/validation/user_email_change.js",
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $userModel = new UserModel();

        //data to update the User Email
        $userModel->setEmail(filter_input(INPUT_POST, "currentEmail", FILTER_SANITIZE_EMAIL));
        $userModel->setNewEmail(filter_input(INPUT_POST, "newEmail", FILTER_SANITIZE_EMAIL));
        $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

        try {

            $userBO = new UserBO();
            $userBO->updateUserEmail($userModel);
            $alertSuccessMessage = "Email successfully updated.";
            $currentEmail = $userModel->getNewEmail();
            $newEmail = "";

            //data to re-create a new UserSessionProfile
            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);

            $userSessionProfile = new UserSessionProfile(
                $userSessionData->getUserId(),
                $userModel->getNewEmail(),
                $userSessionData->getFirstName(),
                $userSessionData->getProfiles()
            );
            $_SESSION[AppConstants::USER_SESSION_DATA] = serialize($userSessionProfile);

        } catch (Exception $e) {
            $currentEmail = $userModel->getEmail();
            $newEmail = $userModel->getNewEmail();
            $alertErrorMessage = $e->getMessage();
        }

    }

    require_once "views/templates/header.html";
    require_once "views/change_email.html";
    require_once "views/templates/footer.html";

}
