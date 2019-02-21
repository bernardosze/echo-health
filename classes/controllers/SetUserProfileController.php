<?php

namespace classes\controllers {

    use Exception;
    use \classes\business\UserBO as UserBO;

    $pageTitle = "Set User Profile";
    $extraCSS = [
        "",
        "https://unpkg.com/bootstrap-table@1.13.4/dist/bootstrap-table.min.css",
    ];
    $extraJS = [
        "https://cdn.jsdelivr.net/npm/flatpickr",
        "https://cdnjs.cloudflare.com/ajax/libs/core-js/2.6.2/core.min.js",
        "https://unpkg.com/bootstrap-table@1.13.4/dist/bootstrap-table.min.js",
        "static/js/validation/set_user_profile.js",
    ];

    //page variables
    $firstName = $lastName = $email = $birthday = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (!empty($_SERVER['QUERY_STRING'])) {

            parse_str($_SERVER['QUERY_STRING'], $qString);

            if (array_key_exists("id", $qString) &&
                !empty($qString["id"])) {

                $userBO = new UserBO();
                try {
                    $userModel = $userBO->fetchUserById($qString["id"]);
                    $firstName = $userModel->getFirstName();
                    $lastName = $userModel->getLastName();
                    $email = $userModel->getEmail();
                    $birthday = $userModel->getBirthday();

                } catch (Exception $e) {
                    $alertErrorMessage = "Error loading user data";
                }

            } else {
                $alertErrorMessage = "Invalid Request: User id not provided.";
            }

        }

    }

    require_once "views/templates/header.html";
    require_once "views/set_user_profile.html";
    require_once "views/templates/footer.html";

}
