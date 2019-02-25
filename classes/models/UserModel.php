<?php

namespace classes\models {

    use JsonSerializable;

    class UserModel implements JsonSerializable
    {
        private $id;
        private $email;
        private $first_name;
        private $last_name;
        private $password;
        private $birthday;
        private $last_login;
        private $last_login_attempt;
        private $login_attempt;
        private $blocked;
        private $record_creation;

        //transient fields
        private $newPassword;
        private $newEmail;

        //USER_PROFILE_MODEL Associative entity to User
        private $userProfile;

        public function __construct()
        {
        }

        public function getId()
        {
            return $this->id;
        }

        public function setId($value)
        {
            $this->id = $value;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($value)
        {
            $this->email = $value;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($value)
        {
            $this->password = $value;
        }

        public function getFirstName()
        {
            return $this->first_name;
        }

        public function setFirstName($value)
        {
            $this->first_name = $value;
        }

        public function getLastName()
        {
            return $this->last_name;
        }

        public function setLastName($value)
        {
            $this->last_name = $value;
        }

        public function getBirthday()
        {
            return $this->birthday;
        }

        public function setBirthday($value)
        {
            $this->birthday = $value;
        }

        public function getLastLogin()
        {
            return $this->last_login;
        }

        public function setLastLogin($value)
        {
            $this->last_login = $value;
        }

        public function getLastLoginAttempt()
        {
            return $this->last_login_attempt;
        }

        public function setLastLoginAttempt($value)
        {
            $this->last_login_attempt = $value;
        }

        public function getLoginAttempt()
        {
            return $this->login_attempt;
        }

        public function setLoginAttempt($value)
        {
            $this->login_attempt = $value;
        }

        public function getBlocked()
        {
            return $this->blocked;
        }

        public function setBlocked($value)
        {
            $this->blocked = $value;
        }

        public function getRecordCreation()
        {
            return $this->record_creation;
        }

        public function setRecordCreation($value)
        {
            $this->record_creation = $value;
        }

        public function setUserProfile($value)
        {
            $this->userProfile = $value;
        }
        public function getUserProfile()
        {
            return $this->userProfile;
        }

        public function getNewPassword()
        {
            return $this->newPassword;
        }

        public function setNewPassword($value)
        {
            $this->newPassword = $value;
        }

        public function getNewEmail()
        {
            return $this->newEmail;
        }

        public function setNewEmail($value)
        {
            $this->newEmail = $value;
        }

        //Basic fields to allow create a new user.
        public function hasEmptyFields()
        {
            return (
                empty(self::getEmail()) ||
                empty(self::getFirstName()) ||
                empty(self::getLastName()) ||
                empty(self::getPassword()) ||
                empty(self::getBirthday())
            );
        }

        //Basic fields to allow update a existing user.
        public function isNotValidForUpdate()
        {
            return (
                empty(self::getEmail()) ||
                empty(self::getFirstName()) ||
                empty(self::getLastName()) ||
                empty(self::getBirthday())
            );
        }

        public function arePasswordsEqual()
        {
            return $this->getPassword() === $this->getNewPassword();
        }

        public function arePasswordsBlank()
        {
            return (empty($this->getPassword())) || (empty($this->getNewPassword()));
        }

        public function jsonSerialize()
        {

            $json = [];
            if (!empty($this->getId())) {
                $json["id"] = $this->getId();
            }

            if (!empty($this->getFirstName())) {
                $json["firstName"] = $this->getFirstName();
            }

            if (!empty($this->getLastName())) {
                $json["lastName"] = $this->getLastName();
            }

            if (!empty($this->getEmail())) {
                $json["email"] = $this->getEmail();
            }

            if (!empty($this->getBirthday())) {
                $json["birthday"] = $this->getBirthday();
            }

            return $json;
        }

    }

}
