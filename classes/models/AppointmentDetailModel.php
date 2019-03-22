<?php

namespace classes\models {

    use JsonSerializable;

    class AppointmentDetailModel 
    {
        private $patient_id;
        private $firstname;
        private $lastname;
        private $birthday;
        private $status;
        
      

        public function __construct()
        {
           
        }
        

        public function getId()
        {
            return $this->patient_id;
        }

        public function setId($value)
        {
            $this->patient_id = $value;
        }

        public function getFirstName()
        {
            return $this->firstname;
        }

        public function setFirstName($value)
        {
            $this->firstname = $value;
        }

        public function getLastName()
        {
            return $this->lastname;
        }

        public function setLastName($value)
        {
            $this->lastname = $value;
        }

        public function getBirthday()
        {
            return $this->birthday;
        }

        public function setBirthday($value)
        {
            $this->birthday = $value;
        }

        // public function getDoctorID()
        // {
        //     return $this->doctor_id;
        // }

        // public function setDoctorID($value)
        // {
        //     $this->doctor_id = $value;
        // }

        public function getStatus()
        {
            return $this->status;
        }

        public function setStatus($value)
        {
            $this->status = $value;
        }


        
        

    }

}
