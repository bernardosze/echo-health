<?php
namespace classes\util\interfaces {

    /**
     * Interface to define common operations for all Controller Classes
     * @author: Leonardo Otoni de Assis
     */
    interface IBaseController
    {

        /**
         * Default method to be invoked by a controller to process a request
         */
        public function processRequest();

    }

}
