<?php

namespace classes\business {

    use \classes\dao\PatientDao as PatientDao;
    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    class PatientBO
    {

        /**
         *
         *
         * @param $patientModel - Patient Model data
         */
        public function savePatientProfile($patientModel)
        {

            $profileDao = new ProfileDao();
            $profileModelArray = $profileDao->getAppProfiles();

            foreach ($profileModelArray as $profileModel) {
                if ($profileModel->getName() == ISecurityProfile::PATIENT) {
                    $patientModel->getUserProfile()->setProfileId($profileModel->getId());
                    break;
                }
            }

            try {
                $profileDao->insertUserProfile(array($patientModel->getUserProfile()));
                $patientDao = new PatientDao();
                $patientDao->insertPatientProfile($patientModel);
            } catch (Exception $e) {
                //
                throw $e;
            }

        }

    }
}
