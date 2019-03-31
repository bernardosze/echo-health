<?php
/**
 * Medical Specialty Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\MedicalSpecialtyDao as MedicalSpecialtyDao;
    use \classes\models\MedicalSpecialtyModel as MedicalSpecialtyModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class MedicalSpecialtyBO
    {
        public function __construct()
        {
        }

        public function getAllMedicalSpecialties()
        {
            $msDao = new MedicalSpecialtyDao();
            return $msDao->getAllMedicalSpecialties();
        }

        /**
         * For a given array of MedicalSpecialtyModel. It will identify all elements to
         * insert, update and delete before to invoke DAO layer.
         */
        public function saveMedicalSpecialties($medicalSpecialtyModelArray)
        {

            //identify itens to insert
            $itensToInsert = [];
            foreach ($medicalSpecialtyModelArray as $medicalSpecialtyModel) {
                if ($medicalSpecialtyModel->isDataToInsert()) {
                    $itensToInsert[] = $medicalSpecialtyModel;
                } else {
                    continue;
                }
            }

            //identify itens to delete
            $itensToDelete = [];
            foreach ($medicalSpecialtyModelArray as $medicalSpecialtyModel) {
                if ($medicalSpecialtyModel->isDataToDelete()) {
                    $itensToDelete[] = $medicalSpecialtyModel;
                } else {
                    continue;
                }
            }

            //identify itens to update
            $itensToCompare = [];
            foreach ($medicalSpecialtyModelArray as $medicalSpecialtyModel) {
                if ($medicalSpecialtyModel->isDataToCompare()) {
                    $itensToCompare[] = $medicalSpecialtyModel;
                } else {
                    continue;
                }
            }

            $msDao = new MedicalSpecialtyDao();
            $itensToUpdate = [];
            if (count($itensToCompare) > 0) {
                //compare all itens from db with cantidates to update
                $medicalSpecialtiesFromDB = $msDao->getAllMedicalSpecialties();
                foreach ($medicalSpecialtiesFromDB as $medicalSpecialtyModelFromDB) {

                    foreach ($itensToCompare as $medicalSpecialty) {
                        if ($medicalSpecialtyModelFromDB->getId() === $medicalSpecialty->getId() &&
                            $medicalSpecialtyModelFromDB->getName() !== $medicalSpecialty->getName()) {
                            $itensToUpdate[] = $medicalSpecialty;
                        } else {
                            continue;
                        }

                    }
                }
            }

            //perfom bulk operatiom
            if (count($itensToDelete) > 0 || count($itensToUpdate) > 0 || count($itensToInsert) > 0) {
                $msDao->saveMedicalSpecialties($itensToUpdate, $itensToInsert, $itensToDelete);
            }

            $medicalSpecialtiesList;
            try {
                $medicalSpecialtiesList = $msDao->getAllMedicalSpecialties();
            } catch (NoDataFoundException $e) {
                $medicalSpecialtiesList = [];
            }
            return $medicalSpecialtiesList;

        }
    }

}
