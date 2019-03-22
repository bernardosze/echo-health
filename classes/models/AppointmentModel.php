<?php

namespace classes\models {

    use JsonSerializable;

    class AppointmentModel 
    {
        public $id;
        private $from;
        private $to;
        public $patient_id;
        private $doctor_id;
        private $status;
        private $nicedate;
        private $nicetime;
        private $dayname;

        public function __construct()
        {
            $id = $this->id;
            $from = $this->from;
        }
        public function __toString()
        {
            return $this->from;
        }

        public function getDayName(){
            return $this->dayname;
        }

        public function setDayName($value){
            $this->dayname=$value;
        }
        public function getNiceDate(){
            return $this->nicedate;
        }
        public function getNiceTime(){
            return $this->nicetime;
        }

        public function setNiceDate($value){
            $this->nicedate=$value;
        }

        public function setNiceTime($value){
            $this->nicetime=$value;
        }
        
        public function getId()
        {
            return $this->id;
        }

        public function setId($value)
        {
            $this->id = $value;
        }

        public function getFrom()
        {
            return $this->from;
        }

        public function setFrom($value)
        {
            $this->from = $value;
        }

        public function getTo()
        {
            return $this->to;
        }

        public function setTo($value)
        {
            $this->to = $value;
        }

        public function getPatientID()
        {
            return $this->patient_id;
        }

        public function setPatientID($value)
        {
            $this->patient_id = $value;
        }

        public function getDoctorID()
        {
            return $this->doctor_id;
        }

        public function setDoctorID($value)
        {
            $this->doctor_id = $value;
        }

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
