<?php
/**
 * App Patient Profile Page Controller
 * Author: Bernardo Sze
 */

namespace classes\controllers {

    use \classes\business\PatientBO as PatientBO;
    use \classes\models\PatientModel as PatientModel;
    use \classes\models\UserProfileModel as UserProfileModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;

    $pageTitle = "Profile";

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

        $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
        $userProfile = new UserProfileModel();
        $userProfile->setUserId($userSessionData->getUserId());
        $patientModel->setUserProfile($userProfile);

        try {
            var_dump("error0");
            $patientBO = new PatientBO();
            var_dump("error1");
            $patientBO->savePatientProfile($patientModel);
            var_dump("error2");
            $alertSuccessMessage = "Profile successfully updated.";
            var_dump("error3");
        } catch (UpdateUserDataException $e) {
            //Set the error message to appear on screen
            $alertErrorMessage = $e->getMessage();
        } catch (\Exception $e) {
            var_dump($userSessionData);
            //require_once RoutesManager::_500_CONTROLLER;
            exit();
        }

    }

    require_once "views/templates/header.html";
    require_once "views/patient_profile.html";
    require_once "views/templates/footer.html";

}
