<?php
/**
 * DAO Class to handle doctor database operations
 * @author: Bernardo Sze
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\models\DoctorModel as DoctorModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class DoctorDao {

        public function getDoctorById($userId) {

            $query = "SELECT * FROM doctor WHERE user_profile_user_id = :userId LIMIT 1";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\DoctorModel");
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if(isset($stmt)){
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * Save a UserProfileModel object into database.
         * It accepts an array of UserProfilesModel and performs
         * multiple operations (Bulk Inserts)
         */
        public function insertDoctorSpecialty($doctorSpecialtyModelArray)
        {
            $query = "INSERT INTO doctor_medical_specialty (doctor_id, medical_specialty_id) VALUES";
            //(:doctor_id, :medical_specialty_id)
            $params = "(?,?)";

            $queryPrefix = [];
            $data = [];
            foreach ($doctorSpecialtyModelArray as $doctorSpecialtyModel) {
                $data[] = $doctorSpecialtyModel->toArray();
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

            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        public function deleteDoctorSpecialty($doctorSpecialtyModelArray)
        {
            $query = "DELETE FROM doctor_medical_specialty WHERE doctor_id=:doctor_id AND medical_specialty_id=:medical_specialty_id";

            try {

                $db = Database::getConnection();
                $db->beginTransaction();

                $stmt = $db->prepare($query);
                foreach ($doctorSpecialtyModelArray as $doctorSpecialtyModel) {
                    $stmt->bindValue(":doctor_id", $doctorSpecialtyModel->getDoctorId());
                    $stmt->bindValue(":medical_specialty_id", $doctorSpecialtyModel->getMedicalSpecialtyId());
                    $stmt->execute();
                }

                $db->commit();

            } catch (PDOException $e) {
                $db->rollback();
                throw $e;
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

    } //END: class

}//END: namespace
