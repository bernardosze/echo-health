<?php
/**
 * User Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\DoctorDao as DoctorDao;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class DoctorBO
    {

        private const NO_SPECIALTY = "Specialties not found in the database. Contact the SysAdmin.";
        private const NO_SPECIAL_PROFILES = "Special Profiles not found in the database. Contact the SysAdmin.";
        /**
         * Default constructor
         */
        public function __construct()
        {
        }

        public function fetchDoctorById($userId)
        {
            $doctor;
            try {
                $doctorDao = new DoctorDao();
                $doctor = $doctorDao->getDoctorById($userId);
            } catch (NoDataFoundException $e) {
                $doctor = new DoctorModel();
            }

            return $doctor;
        }

    }
}
