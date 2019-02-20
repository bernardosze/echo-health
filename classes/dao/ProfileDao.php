<?php

namespace classes\dao {

    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class ProfileDao
    {
        public function getProfilesByUserId($userId)
        {
            $query = "select p.id, p.name " .
                "from profile p inner join user_profile up on p.id = up.profile_id " .
                "inner join user u on up.user_id = u.id " .
                "where u.id = :userId";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);
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

    }

}
