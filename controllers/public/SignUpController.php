<?php
/**
 * Controller to dispatch the user registration form
 *
 * Author: Leonardo Otoni
 */

namespace controllers\publicControllers {

    use Exception;
    use \models\UserModel as UserModel;
    use \util\AppConstants as AppConstants;
    use \util\exceptions\RegisterUserException as RegisterUserException;

    const SIGN_UP_VIEW = "views/security/signup.html";
    $moduleName = AppConstants::MODULE_NAME;
    $email = $firstName = $lastName = $birthday = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        require_once SIGN_UP_VIEW;
        exit;
    } else {
        //form posted
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
        $birthday = filter_input(INPUT_POST, 'birthday');
        $hash = filter_input(INPUT_POST, "hash", FILTER_SANITIZE_STRING);

        try {
            $userModel = new UserModel();
            $userModel->registerUser($email, $firstName, $lastName, $hash, $birthday);
            header("Location: login");
        } catch (RegisterUserException $e) {
            $error_message = "Invalid Registration: " . $e->getMessage();
            require_once SIGN_UP_VIEW;
        } catch (Exception $e) {
            require_once "_500Controller.php";
            exit();
        }
    }

}
