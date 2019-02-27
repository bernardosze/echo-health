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
        //Default template files
        protected const TEMPLATE_HEADER = "views/templates/header.html";
        protected const TEMPLATE_FOOTER = "views/templates/footer.html";

        //methods to be implemented by sub classes.
        abstract protected function doGet();
        abstract protected function doPost();
    }

}
