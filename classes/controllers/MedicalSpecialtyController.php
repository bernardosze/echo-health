<?php

namespace classes\controllers {

    use Exception;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\models\MedicalSpecialtyModel as MedicalSpecialtyModel;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    /**
     * Controller Class for Medical Specialty Registry
     *
     * @author: Leonardo Otoni
     */
    class MedicalSpecialtyController extends AppBaseController
    {

        private const EMPTY_LIST_MSG = "Cannot save an empty list. Set at least one Medical Specialty before click on Save.";

        public function __construct()
        {
            parent::__construct(
                "Medical Specialty Registry",
                ["views/medical_specialty.html"],
                null,
                ["static/js/validation/medical_specialty.js"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {

            // $this->renderView();
            $qString = $_SERVER['QUERY_STRING'];
            if (!empty($qString) && (strpos($qString, 'JSON') !== false)) {
                $this->processJsonGetRequest();
            }

            parent::doGet();

        }

        /**
         * Method override.
         * Process GET requests.
         *
         * Process POST request passing JSON data.
         * It will return data up-to-date to the fronte end.
         */
        protected function doPost()
        {

            $data = [];
            try {

                if (array_key_exists("medicalSpecialty", $_POST)) {

                    $jsonArray = $_POST["medicalSpecialty"];

                    $medicalSpecialtiesArray = [];
                    foreach ($jsonArray as $specialty) {
                        if ($specialty["name"] !== "") {
                            $ms = new MedicalSpecialtyModel();
                            $ms->setId($specialty["id"]);
                            $ms->setName($specialty["name"]);
                            if (\array_key_exists("action", $specialty)) {
                                $ms->setAction($specialty["action"]);
                            }
                            $medicalSpecialtiesArray[] = $ms;
                        }
                    }

                    if (\count($medicalSpecialtiesArray) == 0) {
                        throw new Exception(self::EMPTY_LIST_MSG);
                    }
                    $msBO = new MedicalSpecialtyBO();
                    $result = $msBO->saveMedicalSpecialties($medicalSpecialtiesArray);
                    $data = ["status" => "ok", "data" => $result, "message" => "Data successfully saved."];

                } else {
                    $data = ["status" => "error", "message" => self::EMPTY_LIST_MSG];
                }

            } catch (Exception $e) {
                $data = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($data);
                exit();
            }
        }

        /**
         * Return a JSON response with all Medical Specialties
         *
         */
        private function processJsonGetRequest()
        {
            try {
                $msBO = new MedicalSpecialtyBO();
                $medicalSpecialties = $msBO->getAllMedicalSpecialties();
                $data = ["status" => "ok", "data" => $medicalSpecialties];
            } catch (NoDataFoundException $e) {
                $data = ["status" => "ok", "data" => []];
            } catch (Exception $e) {
                $data = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($data);
                exit();
            }

        }

    }

    new MedicalSpecialtyController();

}
