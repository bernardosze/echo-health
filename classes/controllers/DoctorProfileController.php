<?php
namespace classes\controllers {

    use \classes\business\DoctorBO as DoctorBO;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;

    /**
     * Doctor Profile Controller
     *
     * @author: Bernardo Sze
     */
    class DoctorProfileController extends AppBaseController
    {
        private $doctor;
        private $medicalSpecialties;

        public function __construct() {
            parent::__construct(
                "Doctor Profile Page",
                ["views/doctor_profile.html"]
            );
        }

        protected function doGet() {

            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $userId = $userSessionProfile->getUserId();

            try {
                $doctorBO = new DoctorBO();
                $this->doctor = $doctorBO->fetchDoctorById($userId);

                $msBO = new MedicalSpecialtyBO();
                $this->medicalSpecialties = $msBO->getAllMedicalSpecialties();

            } catch (Exception $e) {
                throw $e;
            }

            parent::doGet();

        }

        protected function doPost()
        {
            
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
