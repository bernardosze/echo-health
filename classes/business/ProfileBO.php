<?php
/**
 * Profile Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use Exception;
    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;

    class ProfileBO
    {

        public function __construct()
        {
        }

        private const NO_SPECIAL_PROFILES = "Special Profiles not found in the database. Contact the SysAdmin.";

        /**
         * Get all application special profiles.
         * It will return an array:
         *  [0] -> App Profiles
         *  [1] -> User Profiles
         */
        public function getSpecialProfiles($userId)
        {
            $profileDao = new ProfileDao();

            $appSpecialProfiles;
            try {
                $appSpecialProfiles = $profileDao->getAppProfiles(ISecurityProfile::PATIENT);
            } catch (Exception $e) {
                throw new NoDataFoundException(self::NO_SPECIAL_PROFILES);
            }

            $userProfiles;
            try {
                $userProfiles = $profileDao->getProfilesByUserId($userId, ISecurityProfile::PATIENT);
            } catch (Exception $e) {
                $userProfiles = null;
            }

            return array($appSpecialProfiles, $userProfiles);

        }

    }

}
