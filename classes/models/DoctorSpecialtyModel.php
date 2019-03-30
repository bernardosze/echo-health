<?php

namespace classes\models {

    class DoctorSpecialtyModel
    {
        private $doctor_id;
        private $medical_specialty_id;

        //PROFILE_MODEL Associative entity to UserProfileModel
        private $specialty;

        public function __constructor()
        {
        }

        public function getDoctorId()
        {
            return $this->doctor_id;
        }

        public function setDoctorId($value)
        {
            $this->doctor_id = $value;
        }

        public function getMedicalSpecialtyId()
        {
            return $this->medical_specialty_id;
        }

        public function setMedicalSpecialtyId($value)
        {
            $this->medical_specialty_id = $value;
        }

        public function setSpecialty($value)
        {
            $this->specialty = $value;
        }

        public function getSpecialty()
        {
            return $this->specialty;
        }

        //helper to transform this into an array
        public function toArray()
        {
            return array(
                "doctor_id" => $this->getDoctorId(),
                "medical_specialty_id" => $this->getMedicalSpecialtyId(),
            );
        }
    }

}
