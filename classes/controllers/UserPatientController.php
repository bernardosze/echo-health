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
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundExcpetion;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    class UserPatientController extends AppBaseController{

        private $patient;
        private $medicalSpecialties;

        public function __construct()
        {
            parent::__construct(
                "Patient Profile Page",
                ["views/patient_profile.html"],
                null,
                null,
                null,
                false
            );
        }

        protected function doGet() {
            
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $userId = $userSessionProfile->getUserId();

            try {
                $patientBO = new PatientBO();
                $this->patient = $patientBO->fetchPatientByUserId($userId);
                //$this->id = $doctor->getId();
            } catch (Exception $e) {
                throw $e;
            }

            parent::doGet();

        }

        protected function doPost() {
            $patient = new PatientModel();
            $patientBO = new PatientBO();

            $userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);
            if (empty($userId)) {
                $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $userId = $userSessionProfile->getUserId();
            }

            $profileId = filter_input(INPUT_POST, "profileId", FILTER_SANITIZE_NUMBER_INT);
            if (empty($profileId)) {
                $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $profiles = $userSessionProfile->getProfiles();
                $profileId = array_search(ISecurityProfile::PATIENT, $profiles);
            }

            if(!empty($_POST["patientId"])) {
                $patient->setId(filter_input(INPUT_POST, "patientId", FILTER_SANITIZE_STRING));  
            }

            $patient->setUserId($userId);
            $patient->setUserProfile($profileId);
            $patient->setEmergencyContact(filter_input(INPUT_POST, "emergencyContact", FILTER_SANITIZE_STRING));
            $patient->setEmergencyRelationship(filter_input(INPUT_POST, "emergencyRelationship", FILTER_SANITIZE_STRING));
            $patient->setEmergencyPhone(filter_input(INPUT_POST, "emergencyPhone", FILTER_SANITIZE_STRING));
            $patient->setInsuranceCompany(filter_input(INPUT_POST, "insuranceCompany", FILTER_SANITIZE_STRING));
            $patient->setInsuranceCertificate(filter_input(INPUT_POST, "insuranceCertificate", FILTER_SANITIZE_STRING));
            $patient->setInsuranceGroupPolicy(filter_input(INPUT_POST, "insuranceGroupPolicy", FILTER_SANITIZE_STRING));

            try {
                $this->patient = $patientBO->SavePatient($patient);
                parent::setAlertSuccessMessage("Profile successfully saved.");
            } catch (Exception $e) {
                parent::setAlertErrorMessage("Error trying to save data:" . $e->getMessage());
            }
            parent::doPost();
        }
        protected function renderViewPages($views) {
            //page scope variables
            $patientId = $this->patient->getId();
            $userId = $this->patient->getUserId();
            $userProfile = $this->patient->getUserProfile();
            $emergencyContact = $this->patient->getEmergencyContact();
            $emergencyRelationship = $this->patient->getEmergencyRelationship();
            $emergencyPhone = $this->patient->getEmergencyPhone();
            $insuranceCompany = $this->patient->getInsuranceCompany();
            $insuranceCertificate = $this->patient->getInsuranceCertificate();
            $insuranceGroupPolicy = $this->patient->getInsuranceGroupPolicy();

            foreach ($views as $view) {
                require_once $view;
            }

        }
    }
    new UserPatientController();
}
