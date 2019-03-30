<?php

namespace classes\models {

    class DoctorModel
    {
        private $id;
        private $user_id;
        private $profile_id;
        private $primary_phone;
        private $secondary_phone;
        private $cspo;

        // List all medical specialcities to populate
        private $specialtiesListAux;

        //PROFILE_MODEL Associative entity to UserProfileModel
        private $profile;

        public function __constructor()
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

        public function getPrimaryPhone()
        {
            return $this->primary_phone;
        }

        public function setPrimaryPhone($value)
        {
            $this->primary_phone = $value;
        }

        public function getSecondaryPhone()
        {
            return $this->secondary_phone;
        }

        public function setSecondaryPhone($value)
        {
            $this->secondary_phone = $value;
        }

        public function getCspo()
        {
            return $this->cspo;
        }

        public function setCspo($value)
        {
            $this->cspo = $value;
        }

        public function setSepecialtiesListAux() {
            $this->specialtiesListAux = $value;
        }

        //helper to transform this into an array
        public function toArray()
        {
            return array(
                "id" => $this->getId(),
                "user_id" => $this->getUserId(),
                "profile_id" => $this->getProfileId(),
                "primary_phone" => $this->getPrimaryPhone(),
                "secondary_phone" => $this->getSecondaryPhone(),
                "cspo" => $this->getCspo(),
            );
        }
    }

}
