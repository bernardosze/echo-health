<?php

namespace classes\controllers {

    use Exception;
    use \classes\business\MedicalSpecialtyBO as MedicalSpecialtyBO;
    use \classes\models\MedicalSpecialtyModel as MedicalSpecialtyModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\MenuManager as MenuManager;

    class MedicalSpecialtyController
    {
        private $pageTitle;
        private $extraJS;

        public function __construct()
        {
            $this->pageTitle = "Medical Specialty Registry";
            $this->extraJS = ["static/js/validation/medical_specialty.js"];
            $this->processRequest();
        }

        private function processRequest()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $this->doGet();
            } else {
                $this->doPost();
            }
        }

        private function doGet()
        {
            $qString = $_SERVER['QUERY_STRING'];
            if (!empty($qString) && (strpos($qString, 'JSON') !== false)) {
                $this->processJsonRequest();
            }

            $this->renderView();
        }

        private function doPost()
        {
            $this->processJsonPost();
        }

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

        private function renderView()
        {
            $pageTitle = $this->pageTitle;
            $extraJS = $this->extraJS;
            $appMenu = MenuManager::getFiltredMenus();

            require_once "views/templates/header.html";
            require_once "views/medical_specialty.html";
            require_once "views/templates/footer.html";
        }

    }

    new MedicalSpecialtyController();

}
