<?php
/**
 * Controller to search users.
 * Author: Leonardo Otoni
 */
namespace classes\controllers {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\util\UserSearchParams as UserSearchParams;

    $pageTitle = "User Search";

    //$specifc components in use on the page
    $bootstrapTableComponent = true;

    $extraJS = [
        "static/js/validation/user_search.js",
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $qString = $_SERVER['QUERY_STRING'];

        if (!empty($qString) && (strpos($qString, 'JSON') !== false)) {

            parse_str($qString, $qStringArray);

            $userBO = new UserBO();
            $json = array();
            try {
                $usp = new UserSearchParams($qStringArray);
                $data = $userBO->fetchUsers($usp);
                $json = ["status" => "ok", "data" => $data];
                echo json_encode($json);
            } catch (Exception $e) {
                $json = ["status" => "error"];
                echo json_encode($json);
            } finally {
                exit();
            }

        } else {
            require_once "views/templates/header.html";
            require_once "views/user_search.html";
            require_once "views/templates/footer.html";
        }

    }

}
