<?php
/**
 * User Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\dao\UserDao as UserDao;
    use \classes\dao\MedicalSpecialtyDao as MedicalSpecialtyDao;
    use \classes\dao\DoctorSpecialtyDao as DoctorSpecialtyDao;
    use \classes\database\Database as Database;
    use \classes\models\UserProfileModel as UserProfileModel;
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
                $medicalSpecialties = $medicalSpecialtyDao->getAllMedicalSpecialties()(ISecurityProfile::DOCTOR);
            } catch (Exception $e) {
                throw new NoDataFoundException(self::NO_SPECIAL_PROFILES);
            }

            $userProfiles;
            try {
                $userProfiles = $profileDao->getProfilesByUserId($userId, ISecurityProfile::DOCTOR);
            } catch (Exception $e) {
                $userProfiles = null;
            }

            return array($appSpecialProfiles, $userProfiles);
        }

        public function fetchDoctorById($userId)
        {
            $doctorDao = new DoctorDao();
            return $doctorDao->getDoctorById($userId);
        }

    }
}
