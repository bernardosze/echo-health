<?php
namespace classes\controllers {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\UserSearchParams as UserSearchParams;

    /**
     * Controller Class for User Search
     *
     * @author: Leonardo Otoni
     */
    class UserSearchController extends AppBaseController
    {
        public function __construct()
        {
            parent::__construct(
                "User Search",
                ["views/user_search.html"],
                null,
                ["static/js/validation/user_search.js"],
                null,
                true
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {

            $qString = $_SERVER['QUERY_STRING'];
            if (!empty($qString) && (strpos($qString, 'JSON') !== false)) {
                $this->processAjaxRequest($qString);
            }

            parent::doGet();
        }

        /**
         * Process a Ajax request.
         *
         * This function will invoke the Business layer to fetch users and send back a JSON object
         * to the Front-end
         *
         * @param $queryString - The request query string with user search parameters
         *
         * @return JSON object
         */
        private function processAjaxRequest($queryString)
        {
            parse_str($queryString, $qStringArray);

            $userBO = new UserBO();
            $json = [];
            try {
                $usp = new UserSearchParams($qStringArray);
                $data = $userBO->fetchUsers($usp);
                $json = ["status" => "ok", "data" => $data];
            } catch (Exception $e) {
                $json = ["status" => "error"];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }
        }

    }

    new UserSearchController();

}
