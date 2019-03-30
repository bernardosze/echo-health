<?php
namespace classes\controllers {

    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\models\MedicalSpecialtyModel as MedicalSpecialtyModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;

    /**
     * Doctor Profile Controller
     *
     * @author: Bernardo Sze
     */
    class DoctorProfileController extends AppBaseController
    {

        public function __construct()
        {
            parent::__construct(
                "Doctor Profile Page",
                ["views/doctor_profile.html"]
            );
        }

        protected function doGet() {
            parse_str($_SERVER['QUERY_STRING'], $qString);

            if (array_key_exists("id", $qString) &&
                !empty($qString["id"])) {

                try {

                    $doctorBO = new DoctorBO();
                    $doctorModel = $doctorBO->fetchDoctorById($qString["id"]);
                    $this->userId = $userModel->getId();
                    $this->firstName = $userModel->getFirstName();
                    $this->lastName = $userModel->getLastName();
                    $this->email = $userModel->getEmail();
                    $this->birthday = $userModel->getBirthday();

                    $profileBO = new ProfileBO();
                    $profilesArray = $profileBO->getSpecialProfiles($qString["id"]);
                    $this->appProfiles = $profilesArray[0];
                    $this->userInEditProfiles = $profilesArray[1];

                } catch (NoDataFoundException $e) {
                    parent::setAlertErrorMessage($e->getMessage());
                } catch (Exception $e) {
                    parent::setAlertErrorMessage(self::USER_NOT_FOUND);
                }

            } else {
                parent::setAlertErrorMessage(self::INVALID_REQUEST);
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
            $userId = $this->userId;
            $cspo = $this->cspo;
            $primaryPhone = $this->primaryPhone;
            $secondaryPhone = $this->secondaryPhone;

            foreach ($views as $view) {
                require_once $view;
            }

        }

    }

    new DoctorProfileController();

}
