<?php
/**
 * Doctor Business Object
 * @author: Bernardo Sze
 */
namespace classes\business {

    use \classes\dao\DoctorDao as DoctorDao;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\models\DoctorSpecialtyModel as DoctorSpecialtyModel;
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

        /**
         *
         * @param DoctorModel $doctor
         */
        public function SaveDoctor(DoctorModel $doctor) {
            $doctorDao = new DoctorDao();

            if (empty($doctor->getId())) {
                //Doctor object has a database id
                $doctor = $doctorDao->insertDoctor($doctor);
            } else {
                $doctorDao->updateDoctorByUserId($doctor);
            }
            return $doctor;
        }

        public function InsertDoctorSpecialty($doctorSpecialtyModelArray) {
            $doctorDao = new DoctorDao();
            if(empty($doctorDao->getDoctorSpecialtyById($doctorSpecialtyModelArray[0]->getDoctorId()))) {
                $doctorSpecialties = $doctorDao->insertDoctorSpecialty($doctorSpecialtyModelArray);
            } else {
                $doctorDao->deleteAllDoctorSpecialty($doctorSpecialtyModelArray);
                $doctorSpecialties = $doctorDao->insertDoctorSpecialty($doctorSpecialtyModelArray);
            }
        }

    }
}
