<?php

namespace classes\util\exceptions {

    use Exception;

    class NoDataFoundException extends Exception
    {
        private const DEFAULT_MSG = "No data fetched from database";

        public function __construct($message = self::DEFAULT_MSG, $code = null)
        {
            $this->message = $message;
            $this->code = $code;
        }

    }
}
