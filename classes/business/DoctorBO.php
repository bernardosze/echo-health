<?php
/**
 * Doctor Business Object
 * @author: Bernardo Sze
 */
namespace classes\business {

    use \classes\dao\DoctorDao as DoctorDao;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class DoctorBO {

        private const NO_SPECIALTY = "Specialties not found in the database. Contact the SysAdmin.";
        private const NO_SPECIAL_PROFILES = "Special Profiles not found in the database. Contact the SysAdmin.";
        /**
         * Default constructor
         */
        public function __construct() {
        }

        public function fetchDoctorByUserId($userId) {
            $doctor;
            try {
                $doctorDao = new DoctorDao();
                $doctor = $doctorDao->getDoctorByUserId($userId);
            } catch (NoDataFoundException $e) {
                $doctor = new DoctorModel();
            }

            return $doctor;
        }

        public function SaveDoctorProfile($doctorId, $doctorModel) {
            try {
                $doctorDao = new DoctorDao();
                
                if(empty($doctorId)) {
                    $doctor = $doctorDao->insertDoctor($doctorModel);
                } else {
                    $doctor = $doctorDao->updateDoctorByUserId($doctorId, $doctorModel);
                }
            } catch(exception $e) {

            }

        }

    }
}
