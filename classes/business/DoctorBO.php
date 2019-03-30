<?php
/**
 * User Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\MedicalSpecialtyDao as MedicalSpecialtyDao;
    use \classes\dao\DoctorDao as DoctorDao;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

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

        public function getDoctorsSpecialties($userId)
        {
            $medicalSpecialtyDao = new MedicalSpecialtyDao();

            $medicalSpecialties;
            try {
                $medicalSpecialties = $medicalSpecialtyDao->getAllMedicalSpecialties();
            } catch (Exception $e) {
                throw new NoDataFoundException(self::NO_SPECIAL_PROFILES);
            }

            return $medicalSpecialties;
        }

        public function fetchDoctorById($userId) {
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
