<?php

namespace classes\business {

    use \classes\dao\PatientDao as PatientDao;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

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
        public function fetchPatientByUserId($userId)
        {
            $patient;
            try {
                $patientDao = new PatientDao();
                $patient = $patientDao->getPatientById($userId);
            } catch (NoDataFoundException $e) {
                $patient = new PatientModel();
            }
            return $patient;
        }

        public function SavePatient(PatientModel $patient)
        {
            $patientDao = new PatientDao();

            if (empty($patient->getId())) {
                //Doctor object has a database id
                $patient = $patientDao->insertPatient($patient);
            } else {
                $patientDao->updatePatientByUserId($patient);
            }
            return $patient;
        }

        // public function savePatientProfile($patientModel)
        // {
        //     $profileDao = new ProfileDao();
        //     $profileModelArray = $profileDao->getAppProfiles();

        //     foreach ($profileModelArray as $profileModel) {
        //         if ($profileModel->getName() == ISecurityProfile::PATIENT) {
        //             $patientModel->getUserProfile()->setProfileId($profileModel->getId());

        //             var_dump($patientModel->getUserProfile()->setProfileId($profileModel->getId()));
        //             break;
        //         }
        //     }

        //     try {
        //         var_dump($patientModel->getUserProfile());
        //         //$profileDao->insertUserProfile(array($patientModel->getUserProfile()));
        //         $patientDao = new PatientDao();
        //         $patientDao->insertPatientProfile($patientModel);
        //     } catch (Exception $e) {
        //         $error = "patientBO";
        //         var_dump($error);
        //         //
        //         throw $e;
        //     }

        // }

    }
}
