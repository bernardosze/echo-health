<?php

namespace classes\models {

    class UserProfileModel
    {
        private $user_id;
        private $profile_id;

        //PROFILE_MODEL Associative entity to UserProfileModel
        private $profile;

        public function __constructor()
        {
        }

        public function getUserId()
        {
            return $this->user_id;
        }

        public function setUserId($value)
        {
            $this->user_id = $value;
        }

        public function getProfileId()
        {
            return $this->profile_id;
        }

        public function setProfileId($value)
        {
            $this->profile_id = $value;
        }

        public function setProfile($value)
        {
            $this->profile = $value;
        }

        public function getProfile()
        {
            return $this->profile;
        }

        //helper to transform this into an array
        public function toArray()
        {
            return array(
                "user_id" => $this->getUserId(),
                "profile_id" => $this->getProfileId(),
            );
        }
    }

}
