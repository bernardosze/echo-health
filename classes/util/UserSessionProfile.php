<?php

namespace classes\util {

    /**
     * Helper Class to hold the basic user profile into session
     * @author: Leonardo Otoni
     */
    class UserSessionProfile
    {
        private $userId;
        private $email;
        private $firstName;
        private $profiles;

        public function __construct($userId, $email, $firstName, $profiles)
        {
            $this->userId = $userId;
            $this->email = $email;
            $this->firstName = $firstName;
            $this->profiles = $profiles;
        }

        public function getUserId()
        {
            return $this->userId;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function getProfiles()
        {
            return $this->profiles;
        }

    }
}
