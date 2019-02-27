<?php

namespace classes\util\base {

    use \classes\util\interfaces\IBaseController as IBaseController;

    /**
     * Abstract Base Controller Class that define basic behaviours and operations for
     * all Controller Classes.
     * @author: Leonardo Otoni
     */
    abstract class AbstractBaseController implements IBaseController
    {
        /**
         * Default template Header File
         */
        protected const TEMPLATE_HEADER = "views/templates/header.html";

        /**
         * Default template Footer File
         */
        protected const TEMPLATE_FOOTER = "views/templates/footer.html";

        /**
         * Http GET request processor
         */
        abstract protected function doGet();

        /**
         * Http POST request processor
         */
        abstract protected function doPost();
    }

}
