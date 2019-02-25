<?php

namespace classes\controllers {

    use Exception;
    use \classes\business\ProfileBO as ProfileBO;
    use \classes\business\UserBO as UserBO;
    use \classes\models\ProfileModel as ProfileModel;
    use \classes\models\UserModel as UserModel;

    $pageTitle = "Set User Profile";

    //$specifc components in use on the page
    $flatpickrComponent = true;
    $bootstrapTableComponent = true;

    $extraJS = [
        "static/js/validation/set_user_profile.js",
    ];

    //page variables
    $userId = $firstName = $lastName = $email = $birthday = $appProfiles = $userInEditProfiles = "";
    //Default page Messages
    const DATA_SAVED = "Data successfully saved.";
    const USER_NOT_FOUND = "Error loading user data";
    const INVALID_REQUEST = "Invalid Request: User id not provided.";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (!empty($_SERVER['QUERY_STRING'])) {

            parse_str($_SERVER['QUERY_STRING'], $qString);

            if (array_key_exists("id", $qString) &&
                !empty($qString["id"])) {

                try {

                    $userBO = new UserBO();
                    $userModel = $userBO->fetchUserById($qString["id"]);
                    $userId = $userModel->getId();
                    $firstName = $userModel->getFirstName();
                    $lastName = $userModel->getLastName();
                    $email = $userModel->getEmail();
                    $birthday = $userModel->getBirthday();

                    $profileBO = new ProfileBO();
                    $profilesArray = $profileBO->getSpecialProfiles($qString["id"]);
                    $appProfiles = $profilesArray[0];
                    $userInEditProfiles = $profilesArray[1];

                } catch (NoDataFoundException $e) {
                    $alertErrorMessage = $e->getMessage();
                } catch (Exception $e) {
                    $alertErrorMessage = USER_NOT_FOUND;
                }

            } else {
                $alertErrorMessage = INVALID_REQUEST;
            }

        }

    } else {

        $userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $birthday = filter_input(INPUT_POST, "birthday", FILTER_SANITIZE_STRING);

        $userModel = new UserModel();
        $userModel->setId($userId);
        $userModel->setFirstName($firstName);
        $userModel->setLastName($lastName);
        $userModel->setEmail($email);
        $userModel->setBirthday($birthday);

        $profiles = filter_input(INPUT_POST, 'profile', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $profileModelArray = [];
        if (!empty($profiles)) {
            foreach ($profiles as $key => $data) {
                $profileModel = new ProfileModel();
                $profileModel->setId($data["id"]);
                $profileModel->setName($data["name"]);
                array_push($profileModelArray, $profileModel);

            }
        }

        try {
            $userBO = new UserBO();
            $userBO->updateUser($userModel);
            $userBO->updateUserProfile($userModel->getId(), $profileModelArray);

            //load profiles to keep data on page
            $profileBO = new ProfileBO();
            $profilesArray = $profileBO->getSpecialProfiles($userId);
            $appProfiles = $profilesArray[0];
            $userInEditProfiles = $profilesArray[1];

            $alertSuccessMessage = DATA_SAVED;
        } catch (Exception $e) {
            $alertErrorMessage = $e->getMessage();
        } finally {

        }

    }

    require_once "views/templates/header.html";
    require_once "views/set_user_profile.html";
    require_once "views/templates/footer.html";

}
