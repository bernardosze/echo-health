<?php
/**
 * Medical Specialty DAO Class
 * @author: Leonardo Otoni
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class MedicalSpecialtyDao
    {

        private const EXCEPTION_ENTRY_NAME_EXISTS = "Operation aborted: Cannot save duplicated Specialty Names into the Database.";
        private const EXCEPTION_ENTRY_NAME_IN_USE = "Operation aborted: Specialty Name cannot be delete because is already in use by one or more Doctors.";

        public function __construct()
        {
        }

        /**
         * Get all Medical Specialties from the Database
         */
        public function getAllMedicalSpecialties()
        {

            $query = "select id, name from medical_specialties order by 1 asc";

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\MedicalSpecialtyModel");
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * It performs bulk operations(insert update and delete) using the same transaction
         * $itensToUpdate - Array of MedicalSpecialtyModel to update
         * $itensToInsert - Array of MedicalSpecialtyModel to insert
         * $itensToDelete - Array of MedicalSpecialtyModel to delet
         */
        public function saveMedicalSpecialties($itensToUpdate = null, $itensToInsert = null, $itensToDelete = null)
        {

            try {

                $db = Database::getConnection();
                $db->beginTransaction();

                if (isset($itensToUpdate) && count($itensToUpdate) > 0) {

                    $updateQry = "update medical_specialties set name=:name where id=:id";
                    $stmt = $db->prepare($updateQry);
                    $stmt->bindParam(":name", $name);
                    $stmt->bindParam(":id", $id);
                    foreach ($itensToUpdate as $medicalSpecialty) {
                        $name = $medicalSpecialty->getName();
                        $id = $medicalSpecialty->getId();
                        try {
                            $stmt->execute();
                        } catch (PDOException $e) {
                            throw new PDOException(self::EXCEPTION_ENTRY_NAME_EXISTS);
                        }
                    }

                }

                if (isset($itensToDelete) && count($itensToDelete) > 0) {

                    $deleteQry = "delete from medical_specialties where id=:id";
                    $stmt = $db->prepare($deleteQry);
                    $stmt->bindParam(":id", $id);
                    foreach ($itensToDelete as $medicalSpecialty) {
                        $id = $medicalSpecialty->getId();
                        try {
                            $stmt->execute();
                        } catch (PDOException $e) {
                            throw new PDOException(self::EXCEPTION_ENTRY_NAME_IN_USE);
                        }
                    }

                }

                if (isset($itensToInsert) && count($itensToInsert) > 0) {

                    $insertQry = "insert into medical_specialties (name) values (:name)";
                    $stmt = $db->prepare($insertQry);
                    $stmt->bindParam(":name", $name);
                    foreach ($itensToInsert as $medicalSpecialty) {
                        $name = $medicalSpecialty->getName();
                        try {
                            $stmt->execute();
                        } catch (PDOException $e) {
                            throw new PDOException(self::EXCEPTION_ENTRY_NAME_EXISTS);
                        }
                    }

                }

                $db->commit();

            } catch (\Exception $e) {
                $db->rollback();
                throw $e;
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

    }

}
