<?php

namespace classes\util\exceptions {

    use Exception;

    /**
     * Must be used to throw Critical Exceptions, like an unreachable database.
     * @author: Leonardo Otoni
     */
    class FatalException extends Exception
    {

    }

}
