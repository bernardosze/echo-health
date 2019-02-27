<?php
/**
 * Controller to dispatch the user registration form
 *
 * @author: Leonardo Otoni
 */

namespace classes\controllers\publicControllers {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;
    use \routes\RoutesManager as RoutesManager;

    const SIGN_UP_VIEW = "views/security/signup.html";
    $moduleName = AppConstants::MODULE_NAME;
    $email = $firstName = $lastName = $birthday = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        require_once SIGN_UP_VIEW;
        exit;
    } else {

        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_EMAIL);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
        $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);

        //form posted
        $userModel = new UserModel();
        $userModel->setEmail($email);
        $userModel->setFirstName($firstName);
        $userModel->setLastName($lastName);
        $userModel->setBirthday($birthday);
        $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

        try {
            $userBO = new UserBO();
            $userBO->registerUser($userModel);
            header("Location: login");
        } catch (RegisterUserException $e) {
            $error_message = "Invalid Registration: " . $e->getMessage();
            require_once SIGN_UP_VIEW;
        } catch (Exception $e) {
            require_once RoutesManager::_500_CONTROLLER;
            exit();
        }
    }

}
