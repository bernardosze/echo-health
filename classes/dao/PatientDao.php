<?php
/**
 * DAO Class to handle all Patient Profile database operations
 * @author: Bernardo Sze
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class PatientDao {

        public function getPatientById($userId) {

            $query = "SELECT * FROM patients WHERE user_profile_user_id = :userId LIMIT 1";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\PatientModel");
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }
        public function insertPatient(PatientModel $patient) {

            $query = "INSERT INTO patients (user_profile_user_id,
            user_profile_profile_id,
            emergency_contact,
            emergency_relationship,
            emergency_phone,
            insurance_company,
            insurance_certificate,
            insurance_group_policy) " .

            "values( :userId,
                :userProfileId,
                :emergencyContact,
                :emergencyRelationship,
                :emergencyPhone,
                :insuranceCompany,
                :insuranceCertificate,
                :insuranceGroupPolicy )";

        try {

            $db = Database::getConnection();

            /* It is necessary to guarantee that a User and UserProfile are saved
             * in the same transaction. All users initially has a PATIENT profile
             */
            $db->beginTransaction();

            $stmt = $db->prepare($query);
            $stmt->bindValue(":userId", $patient->getUserId());
            $stmt->bindValue(":userProfileId", $patient->getUserProfile());
            $stmt->bindValue(":emergencyContact", $patient->getEmergencyContact());
            $stmt->bindValue(":emergencyRelationship", $patient->getEmergencyRelationship());
            $stmt->bindValue(":emergencyPhone", $patient->getEmergencyPhone());
            $stmt->bindValue(":insuranceCompany", $patient->getInsuranceCompany());
            $stmt->bindValue(":insuranceCertificate", $patient->getInsuranceCertificate());
            $stmt->bindValue(":insuranceGroupPolicy", $patient->getInsuranceGroupPolicy());
                $stmt->execute();

                $patient->setId($db->lastInsertId());

                $db->commit();

            } catch (PDOException $e) {
                $db->rollback();
                throw $e;
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
                return $patient;
            }

        }

        public function updatePatientByUserId($patient) {

            $query = "UPDATE patients SET 
                        emergency_contact=:emergencyContact,
                        emergency_relationship=:emergencyRelationship,
                        emergency_phone=:emergencyPhone,
                        insurance_company=:insuranceCompany,
                        insurance_certificate=:insuranceCertificate,
                        insurance_group_policy=:insuranceGroupPolicy
                            WHERE id=:id";

            try {

                $db = Database::getConnection();
                $db->beginTransaction();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":id", $patient->getId());
                    $stmt->bindValue(":emergencyContact", $patient->getEmergencyContact());
                    $stmt->bindValue(":emergencyRelationship", $patient->getEmergencyRelationship());
                    $stmt->bindValue(":emergencyPhone", $patient->getEmergencyPhone());
                    $stmt->bindValue(":insuranceCompany", $patient->getInsuranceCompany());
                    $stmt->bindValue(":insuranceCertificate", $patient->getInsuranceCertificate());
                    $stmt->bindValue(":insuranceGroupPolicy", $patient->getInsuranceGroupPolicy());
                    $stmt->execute();

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

        /**
         *
         * @param $patientModel -
         */
        // public function insertPatientProfile(PatientModel $patientModel) {

        //     $insertPatientProfileQuery =
        //         "INSERT INTO patients (user_profile_user_id,
        //         user_profile_profile_id,
        //         emergency_contact,
        //         emergency_relationship,
        //         emergency_phone,
        //         insurance_company,
        //         insurance_certificate,
        //         insurance_group_policy) " .

        //         "values( :userId,
        //             :userProfileId,
        //             :emergencyContact,
        //             :emergencyRelationship,
        //             :emergencyPhone,
        //             :insuranceCompany,
        //             :insuranceCertificate,
        //             :insuranceGroupPolicy )";

        //     try {

        //         $db = Database::getConnection();

        //         /* It is necessary to guarantee that a User and UserProfile are saved
        //          * in the same transaction. All users initially has a PATIENT profile
        //          */
        //         $db->beginTransaction();

        //         $stmt = $db->prepare($insertPatientProfileQuery);
        //         $stmt->bindValue(":userId", $patientModel->getUserId());
        //         $stmt->bindValue(":userProfileId", $patientModel->getUserProfile());
        //         $stmt->bindValue(":emergencyContact", $patientModel->getEmergencyContact());
        //         $stmt->bindValue(":emergencyRelationship", $patientModel->getEmergencyRelationship());
        //         $stmt->bindValue(":emergencyPhone", $patientModel->getEmergencyPhone());
        //         $stmt->bindValue(":insuranceCompany", $patientModel->getInsuranceCompany());
        //         $stmt->bindValue(":insuranceCertificate", $patientModel->getInsuranceCertificate());
        //         $stmt->bindValue(":insuranceGroupPolicy", $patientModel->getInsuranceGroupPolicy());
        //         $stmt->execute();

        //         $db->commit();

        //     } catch (PDOException $e) {

        //         $db->rollBack();

        //         if ($e->getCode() == 23000) {
        //             //Email in duplicity
        //             throw new RegisterUserException(self::USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION);
        //         } else {
        //             throw $e;
        //         }

        //     } catch (Exception $e) {
        //         $db->rollBack();
        //         throw $e;
        //     } finally {
        //         $stmt->closeCursor();
        //     }

        // }
    }
}
