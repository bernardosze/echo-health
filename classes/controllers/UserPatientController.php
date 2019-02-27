<?php
/**
 * App Patient Profile Page Controller
 * Author: Bernardo Sze
 */

namespace classes\controllers {

    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\dao\UserDao as UserDao;
    use \classes\dao\PatientDao as PatientDao;
    use \classes\database\Database as Database;
    use \classes\models\UserModel as UserModel;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \routes\RoutesManager as RoutesManager;

    $pageTitle = "Profile";
    $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
    $userEmail = $userSessionData->getEmail();
    $emergencyContact = $emergencyRelationship = $emergencyPhone = "";
    $insuranceCompany = $insuranceCertificate = $insuranceGroupPolicy = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $patientModel = new PatientModel();

        $patientModel->setEmergencyContact(filter_input(INPUT_POST, "emergencyContact", FILTER_SANITIZE_STRING));
        $patientModel->setEmergencyRelationship(filter_input(INPUT_POST, "emergencyRelationship", FILTER_SANITIZE_STRING));
        $patientModel->setEmergencyPhone(filter_input(INPUT_POST, "emergencyPhone", FILTER_SANITIZE_STRING));
        $patientModel->setInsuranceCompany(filter_input(INPUT_POST, "insuranceCompany", FILTER_SANITIZE_STRING));
        $patientModel->setInsuranceCertificate(filter_input(INPUT_POST, "insuranceCertificate", FILTER_SANITIZE_STRING));
        $patientModel->setInsuranceGroupPolicy(filter_input(INPUT_POST, "insuranceGroupPolicy", FILTER_SANITIZE_STRING));

        try {
            $patientDao = new PatientDao();
            $patientDao->insertPatientProfile($patientModel);
            $alertSuccessMessage = "Profile successfully updated.";
        } catch (UpdateUserDataException $e) {
            //Set the error message to appear on screen
            $alertErrorMessage = $e->getMessage();
        } catch (\Exception $e) {
            require_once RoutesManager::_500_CONTROLLER;
            exit();
        }

    }

    require_once "views/templates/header.html";
    require_once "views/patient_profile.html";
    require_once "views/templates/footer.html";

}