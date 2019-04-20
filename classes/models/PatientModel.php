<?php

namespace classes\models {

    //use \classes\models\ProfileModel as ProfileModel;
    use JsonSerializable;

    class PatientModel implements JsonSerializable {
        private $id;
        private $userId;
        private $userProfile;
        private $emergency_contact;
        private $emergency_relationship;
        private $emergency_phone;
        private $insurance_company;
        private $insurance_certificate;
        private $insurance_group_policy;

        //aux UserProfileModel entity

        //private $userProfile;
        

        public function __construct() {
            //$userProfile = new ProfileModel();
        }

        public function getId() {
            return $this->id;
        }

        public function setId($value) {
            $this->id = $value;
        }

        public function getUserId() {
            
            return $this->userId;
        }

        public function setUserId($value) {
            $this->userId = $value;
        }

        public function getUserProfile() {
            
            return $this->userProfile;
        }

        public function setUserProfile($value) {
            $this->userProfile = $value;
        }

        public function getEmergencyContact() {
            return $this->emergency_contact;
        }

        public function setEmergencyContact($value) {
            $this->emergency_contact = $value;
        }

        public function getEmergencyRelationship() {
            return $this->emergency_relationship;
        }

        public function setEmergencyRelationship($value) {
            $this->emergency_relationship = $value;
        }

        public function getEmergencyPhone() {
            return $this->emergency_phone;
        }

        public function setEmergencyPhone($value) {
            $this->emergency_phone = $value;
        }

        public function getInsuranceCompany() {
            return $this->insurance_company;
        }

        public function setInsuranceCompany($value) {
            $this->insurance_company = $value;
        }

        public function getInsuranceCertificate() {
            return $this->insurance_certificate;
        }

        public function setInsuranceCertificate($value) {
            $this->insurance_certificate = $value;
        }

        public function getInsuranceGroupPolicy() {
            return $this->insurance_group_policy;
        }

        public function setInsuranceGroupPolicy($value) {
            $this->insurance_group_policy = $value;
        }

        public function jsonSerialize() {

            $json = [];
            if (!empty($this->getUserProfileUserId())) {
                $json["userId"] = $this->getUserProfileUserId();
            }

            if (!empty($this->getEmergencyContact())) {
                $json["emergencyContact"] = $this->getEmergencyContact();
            }

            if (!empty($this->getEmergencyRelationship())) {
                $json["emergencyRelationship"] = $this->getEmergencyRelationship();
            }

            if (!empty($this->getEmergencyPhone())) {
                $json["emergencyPhone"] = $this->getEmergencyPhone();
            }

            if (!empty($this->getInsuranceCompany())) {
                $json["insuranceCompany"] = $this->getInsuranceCompany();
            }

            if (!empty($this->getInsuranceCertificate())) {
                $json["insuranceCertificate"] = $this->getInsuranceCertificate();
            }

            if (!empty($this->getInsuranceGroupPolicy())) {
                $json["insuranceGroupPolicy"] = $this->getInsuranceGroupPolicy();
            }

            return $json;
        }

    }

}
