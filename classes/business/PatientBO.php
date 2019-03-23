<?php

namespace classes\business {

    use \classes\dao\PatientDao as PatientDao;
    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    class PatientBO
    {
        public function __construct()
        {
        }

        /**
         *
         *
         * @param $patientModel - Patient Model data
         */
        public function savePatientProfile($patientModel)
        {
            var_dump("PatientBO-0");
            $profileDao = new ProfileDao();
            var_dump("PatientBO-1");
            $profileModelArray = $profileDao->getAppProfiles();
            var_dump("PatientBO-2");

            foreach ($profileModelArray as $profileModel) {
                if ($profileModel->getName() == ISecurityProfile::PATIENT) {
                    $patientModel->getUserProfile()->setProfileId($profileModel->getId());

                    var_dump("PatientBO-3");
                    var_dump($patientModel->getUserProfile()->setProfileId($profileModel->getId()));
                    break;
                }
            }

            try {
                var_dump("PatientBO-4");
                var_dump($patientModel->getUserProfile());
                $profileDao->insertUserProfile(array($patientModel->getUserProfile()));
                var_dump("PatientBO-5");
                $patientDao = new PatientDao();
                var_dump("PatientBO-6");
                $patientDao->insertPatientProfile($patientModel);
                var_dump("PatientBO-7");
            } catch (Exception $e) {
                $error = "patientBO";
                var_dump($error);
                //
                throw $e;
            }

        }

    }
}
