<?php

namespace classes\models {

    use JsonSerializable;

    class MedicalHistoryModel 
    {
        
        private $id;
        private $appointment_id;
        private $patient_id;
        private $date;
        private $assessment;
        private $prescription;
        
        public function __construct()
        {
           
        }
        public function __toString()
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
        public function getApptId()
        {
            return $this->appointment_id;
        }

        public function setApptId($value)
        {
            $this->appointment_id = $value;
        }

        public function getPatientId()
        {
            return $this->patient_id;
        }

        public function setPatientId($value)
        {
            $this->patient_id = $value;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function setDate($value)
        {
            $this->date = $value;
        }

        public function getAssessment()
        {
            return $this->assessment;
        }

        public function setAssessment($value)
        {
            $this->assessment = $value;
        }

        // public function getDoctorID()
        // {
        //     return $this->doctor_id;
        // }

        // public function setDoctorID($value)
        // {
        //     $this->doctor_id = $value;
        // }
        public function getPrescription()
        {
            return $this->prescription;
        }

        public function setPrescription($value)
        {
            $this->prescription = $value;
        }
        
    }

}
