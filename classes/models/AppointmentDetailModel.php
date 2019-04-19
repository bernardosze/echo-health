<?php

namespace classes\models {

    use JsonSerializable;

    class AppointmentDetailModel 
    {
        private $aid;
        private $id;
        private $first_name;
        private $last_name;
        private $birthday;
        private $status;
        private $from;
        
        public function __construct()
        {
           
        }
        public function __toString()
        {
            
        }
        public function getApptId()
        {
            return $this->aid;
        }

        public function setApptId($value)
        {
            $this->aid = $value;
        }
        public function getId()
        {
            return $this->id;
        }

        public function setId($value)
        {
            $this->id = $value;
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
        public function getFrom()
        {
            return $this->from;
        }

        public function setFrom($value)
        {
            $this->from = $value;
        }
    }

}
