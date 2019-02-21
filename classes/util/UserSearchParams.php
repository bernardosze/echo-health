<?php

namespace classes\util {

    /**
     * Wrapper Class used to pass User search Params to business class
     */
    class UserSearchParams
    {
        private $email;
        private $firstName;
        private $lastName;
        private $blocked;

        public function __construct($array)
        {
            $this->email = array_key_exists("email", $array) ? $array["email"] : null;
            $this->firstName = array_key_exists("firstName", $array) ? $array["firstName"] : null;
            $this->lastName = array_key_exists("lastName", $array) ? $array["lastName"] : null;
            $this->blocked = array_key_exists("blocked", $array) ? $array["blocked"] : null;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function getLastName()
        {
            return $this->lastName;
        }

        public function getBlocked()
        {
            return $this->blocked;
        }

        public function setEmail($value)
        {
            $this->email = $value;
        }

        public function setFirstName($value)
        {
            $this->firstName = $value;
        }

        public function setLastName($value)
        {
            $this->lastName = $value;
        }

        public function setBlocked($value)
        {
            $this->blocked = $value;
        }

        /**
         * provide an associative array.
         */
        public function toArray()
        {
            $array = [];
            if (!empty($this->getEmail())) {
                $array["email"] = $this->getEmail();
            }

            if (!empty($this->getFirstName())) {
                $array["first_name"] = $this->getFirstName();
            }

            if (!empty($this->getLastName())) {
                $array["last_name"] = $this->getLastName();
            }

            if (!empty($this->getBlocked())) {
                $array["blocked"] = $this->getBlocked();
            }

            return $array;

        }

    }
}
