<?php
namespace classes\controllers {

    use Exception;
    use \classes\business\DoctorBO as DoctorBO;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundExcpetion;

    /**
     * Doctor Profile Controller
     *
     * @author: Bernardo Sze
     */
    class DoctorProfileController extends AppBaseController {
        private $id = "";
        private $userId = "";
        private $profileId = "";
        private $cspo = "";
        private $primaryPhone = "";
        private $secondaryPhone = "";

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
            $profileId = $userSessionProfile->getProfiles();

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
            $this->cspo = filter_input(INPUT_POST, "cspo", FILTER_SANITIZE_NUMBER_INT);
            $this->primaryPhone = filter_input(INPUT_POST, "primaryPhone", FILTER_SANITIZE_NUMBER_INT);
            $this->secondaryPhone = filter_input(INPUT_POST, "secondaryPhone", FILTER_SANITIZE_NUMBER_INT);

            $doctorModel = new DoctorModel();
            $doctorModel->setId($this->id);
            $doctorModel->setUserId($this->userId);
            $doctorModel->setProfileId($this->profileId);
            $doctorModel->setPrimaryPhone($this->primaryPhone);
            $doctorModel->setSecondaryPhone($this->secondaryPhone);
            $doctorModel->setCspo($this->cspo);

            $json = [];
            try {
                $userBO = new UserBO();
                $doctorBO->SaveDoctorProfile($doctorId, $doctorModel);
                $json = ["status" => "ok", "message" => self::DATA_SAVED];
            } catch (Exception $e) {
                $json = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
            }

            parent::doPost();
        }

        protected function renderViewPages($views) {
            //page scope variables
            $userId = $this->doctor->getUserId();
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
