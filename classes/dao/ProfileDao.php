<?php
/**
 * DAO Class to handle all Profile database operations
 * Author: Leonardo Otoni
 */
namespace classes\dao {

    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class ProfileDao
    {
        /**
         * Get All user Profiles registered into the Database.
         * It ignores the basic user profile
         * $userId - User Id to get all profiles associated
         * $profileToIgnore - [Optional] - If not null, will exclude the profileName in the query
         */
        public function getProfilesByUserId($userId, $profileToIgnore = null)
        {

            $qExcludeProfile = "select p.id, p.name " .
                "from profile p inner join user_profile up on p.id = up.profile_id " .
                "inner join user u on up.user_id = u.id " .
                "where u.id = :userId and " .
                " p.name <> :profileName";

            $qAllProfiles = "select p.id, p.name " .
                "from profile p inner join user_profile up on p.id = up.profile_id " .
                "inner join user u on up.user_id = u.id " .
                "where u.id = :userId";

            $query = (!empty($profileToIgnore) ? $qExcludeProfile : $qAllProfiles);

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);

                if ($query === $qExcludeProfile) {
                    $stmt->bindValue(":profileName", $profileToIgnore);
                }

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\ProfileModel");
                } else {
                    throw new NoDataFoundException();
                }

            } catch (PDOException $e) {
                throw $e;
            } finally {
                $stmt->closeCursor();
            }
        }

        /**
         * Get all application Profiles.
         * $profileToIgnore - [Optional] - If not null, will exclude the profileName in the query
         */
        public function getAppProfiles($profileToIgnore = null)
        {
            $queryAllProfiles = "select p.id, p.name from profile p ";
            $queryIgnoringProfile = "select p.id, p.name from profile p where p.name <> :profileToIgnore";
            $query = (empty($profileToIgnore) ? $queryAllProfiles : $queryIgnoringProfile);

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);

                if ($query === $queryIgnoringProfile) {
                    $stmt->bindValue(":profileToIgnore", $profileToIgnore);
                }

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\ProfileModel");
                } else {
                    throw new NoDataFoundException();
                }

            } catch (PDOException $e) {
                throw $e;
            } finally {
                $stmt->closeCursor();
            }
        }

        /**
         * Save a UserProfileModel object into database.
         * It accepts an array of UserProfilesModel and performs
         * multiple operations (Bulk Inserts)
         */
        public function insertUserProfile($userProfileModelArray)
        {
            $query = "insert into user_profile (user_id, profile_id) values";
            //(:user_id, :profile_id)
            $params = "(?,?)";

            $queryPrefix = [];
            $data = [];
            foreach ($userProfileModelArray as $userProfileModel) {
                $data[] = $userProfileModel->toArray();
                $queryPrefix[] = $params;
            }
            //sets the final query having the amount of parameters.
            $query = $query . " " . implode(",", $queryPrefix);

            //create a new sequential array of pure data
            $values = [];
            foreach ($data as $row) {
                foreach ($row as $column => $value) {
                    $values[] = $value;
                }
            }

            try {

                $db = Database::getConnection();
                $db->beginTransaction();

                $stmt = $db->prepare($query);
                $stmt->execute($values);

                $db->commit();

            } catch (PDOException $e) {
                $db->rollback();
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

        public function deleteUserProfile($userProfileModelArray)
        {
            $query = "delete from user_profile where user_id=:userId and profile_id=:profileId";

            try {

                $db = Database::getConnection();
                $db->beginTransaction();

                $stmt = $db->prepare($query);
                foreach ($userProfileModelArray as $userProfileModel) {
                    $stmt->bindValue(":userId", $userProfileModel->getUserId());
                    $stmt->bindValue(":profileId", $userProfileModel->getProfileId());
                    $stmt->execute();
                }

                $db->commit();

            } catch (PDOException $e) {
                $db->rollback();
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

    }

}
