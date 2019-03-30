<?php
/**
 * DAO Class to handle all Patient Profile database operations
 * Author: Leonardo Otoni
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\models\PatientModel as PatientModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;

    class PatientDao
    {
        public function __construct()
        {
        }

        //TODO, Method duplicated. It must to be removed.
        public function getUserById($userId)
        {

            $query = "SELECT * FROM users WHERE id = :userId AND blocked <> 'Y' LIMIT 1";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\UserModel");
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
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
         *
         * afdsfsdfdsfsdfs
         *
         * @param $patientModel -
         */
        public function insertPatientProfile($patientModel)
        {

            $insertPatientProfileQuery =
                "INSERT INTO patients (user_profile_user_id,
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

                $stmt = $db->prepare($insertPatientProfileQuery);
                $stmt->bindValue(":userId", $patientModel->getUserProfile->getUserId());
                $stmt->bindValue(":userProfileId", $patientModel->getUserProfile->getProfileId());
                $stmt->bindValue(":emergencyContact", $patientModel->getEmergencyContact());
                $stmt->bindValue(":emergencyRelationship", $patientModel->getEmergencyRelationship());
                $stmt->bindValue(":emergencyPhone", $patientModel->getEmergencyPhone());
                $stmt->bindValue(":insuranceCompany", $patientModel->getInsuranceCompany());
                $stmt->bindValue(":insuranceCertificate", $patientModel->getInsuranceCertificate());
                $stmt->bindValue(":insuranceGroupPolicy", $patientModel->getInsuranceGroupPolicy());
                $stmt->execute();

                $db->commit();

            } catch (PDOException $e) {

                $db->rollBack();

                if ($e->getCode() == 23000) {
                    //Email in duplicity
                    throw new RegisterUserException(self::USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION);
                } else {
                    throw $e;
                }

            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }
    }
}
