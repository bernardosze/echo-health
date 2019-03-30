<?php
namespace classes\controllers {

    use \classes\business\DoctorBO as DoctorBO;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\models\MedicalSpecialtyModel as MedicalSpecialtyModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;

    /**
     * Doctor Profile Controller
     *
     * @author: Bernardo Sze
     */
    class DoctorProfileController extends AppBaseController {
        private $doctor;


        public function __construct()
        {
            parent::__construct(
                "Doctor Profile Page",
                ["views/doctor_profile.html"]
            );
        }

        protected function doGet() {
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $id = $userSessionProfile->getUserId();

            try {

                $doctorBO = new DoctorBO();
                $this->doctor = $doctorBO->fetchDoctorById($qString["id"]);

            } catch (NoDataFoundException $e) {
                parent::setAlertErrorMessage($e->getMessage());
            } 

            parent::doGet();

        }

        protected function getMedicalSpecialtyList(){
            $msBO = new MedicalSpecialtyBO();
            $medicalSpecialties = $msBO->getAllMedicalSpecialties();
        }

        protected function renderViewPages($views)
        {
            //page scope variables
            $userId = $this->doctor->getUserId();
            $cspo = $this->doctor->getCspo();
            $primaryPhone = $this->doctor->getPrimaryPhone();
            $secondaryPhone = $this->doctor->getSecondaryPhone();

            foreach ($views as $view) {
                require_once $view;
            }

        }

    }

    new DoctorProfileController();

}
