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
                $this->processJsonRequest();
            }

            parent::doGet();

        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doPost()
        {
            $this->processJsonPost();
            parent::doPost();
        }

        /**
         * Return a JSON response with all Medical Specialties
         *
         */
        private function processJsonRequest()
        {
            try {
                $msBO = new MedicalSpecialtyBO();
                $medicalSpecialties = $msBO->getAllMedicalSpecialties();
                $data = ["status" => "ok", "data" => $medicalSpecialties];
            } catch (NoDataFoundException $e) {
                $data = ["status" => "ok", "data" => []];
            } catch (Exception $e) {
                $data = ["status" => "error", "message" => $e->getMessage()];
            }

            echo json_encode($data);
            exit();
        }

        /**
         * Process POST request passing JSON data.
         * It will return data up-to-date to the fronte end.
         */
        private function processJsonPost()
        {
            $jsonArray = $_POST["medicalSpecialty"];

            try {

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

                $msBO = new MedicalSpecialtyBO();
                $result = $msBO->saveMedicalSpecialties($medicalSpecialtiesArray);
                $data = ["status" => "ok", "data" => $result, "message" => "Data successfully saved."];
            } catch (\Exception $e) {
                $data = ["status" => "error", "message" => $e->getMessage()];
            }

            echo json_encode($data);
            exit();

        }

    }

    new MedicalSpecialtyController();

}
