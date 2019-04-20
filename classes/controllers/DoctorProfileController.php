<?php
namespace classes\controllers {

    use Exception;
    use \classes\business\DoctorBO as DoctorBO;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\models\DoctorSpecialtyModel as DoctorSpecialtyModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundExcpetion;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    /**
     * Doctor Profile Controller
     *
     * @author: Bernardo Sze
     */
    class DoctorProfileController extends AppBaseController {

        private $doctor;
        private $medicalSpecialties;
        private $doctorMedicalSpecialties;

        public function __construct() {
            parent::__construct(
                "Doctor Profile Page",
                ["views/doctor_profile.html"],
                null,
                ["static/js/doctor_profile.js"],
                null,
                false
            );
        }

        protected function doGet() {

            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $userId = $userSessionProfile->getUserId();

            try {
                $doctorBO = new DoctorBO();
                $this->doctor = $doctorBO->fetchDoctorByUserId($userId);
                //$this->id = $doctor->getId();
            } catch (Exception $e) {
                throw $e;
            }

            try {
                $msBO = new MedicalSpecialtyBO();
                $this->medicalSpecialties = $msBO->getAllMedicalSpecialties();
            } catch (NoDataFoundExcpetion $e) {
                $this->medicalSpecialties = null;
            } catch (Exception $e) {
                throw $e;
            }

            parent::doGet();

        }

        protected function doPost() {
            $doctor = new DoctorModel();
            $doctorBO = new DoctorBO();

            $doctor->setId(filter_input(INPUT_POST, "doctorId", FILTER_SANITIZE_NUMBER_INT));

            $userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);
            if (empty($userId)) {
                $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $userId = $userSessionProfile->getUserId();
            }

            $profileId = filter_input(INPUT_POST, "profileId", FILTER_SANITIZE_NUMBER_INT);
            if (empty($profileId)) {
                $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $profiles = $userSessionProfile->getProfiles();
                $profileId = array_search(ISecurityProfile::DOCTOR, $profiles);
            }

            $doctor->setUserId($userId);
            $doctor->setProfileId($profileId);
            $doctor->setCspo(filter_input(INPUT_POST, "cspo", FILTER_SANITIZE_NUMBER_INT));
            $doctor->setPrimaryPhone(filter_input(INPUT_POST, "primaryPhone", FILTER_SANITIZE_NUMBER_INT));
            $doctor->setSecondaryPhone(filter_input(INPUT_POST, "secondaryPhone", FILTER_SANITIZE_NUMBER_INT));

            ///
            if(!empty($_POST["medicalSpecialtySelection"])) {
                $selectedMedicalSpecialties = $_POST["medicalSpecialtySelection"];//filter_input(INPUT_POST, "medicalSpecialtySelection");
                $doctorMedicalSpecialties=[];  
                foreach ($selectedMedicalSpecialties as $key) {
                    $doctorSpecialties = new DoctorSpecialtyModel();
                    $doctorSpecialties->setDoctorId($doctor->getId());
                    $doctorSpecialties->setMedicalSpecialtyId($key);
                    $doctorMedicalSpecialties[] = $doctorSpecialties;
                }
                $doctorBO->InsertDoctorSpecialty($doctorMedicalSpecialties);
            }

            try {
                $this->doctor = $doctorBO->SaveDoctor($doctor);

                ///
                parent::setAlertSuccessMessage("Profile successfully saved.");
            } catch (Exception $e) {
                parent::setAlertErrorMessage("Error trying to save data:" . $e->getMessage());
            }

            parent::doPost();
        }

        protected function renderViewPages($views) {
            //page scope variables
            $doctorId = $this->doctor->getId();
            $userId = $this->doctor->getUserId();
            $profileId = $this->doctor->getProfileId();
            $cspo = $this->doctor->getCspo();
            $primaryPhone = $this->doctor->getPrimaryPhone();
            $secondaryPhone = $this->doctor->getSecondaryPhone();

            $medicalSpecialties = $this->medicalSpecialties;

            foreach ($views as $view) {
                require_once $view;
            }

        }

    }

    new DoctorProfileController();

}