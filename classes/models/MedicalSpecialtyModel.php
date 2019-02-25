<?php

namespace classes\models {

    use JsonSerializable;

    class MedicalSpecialtyModel implements JsonSerializable
    {

        public function __construct()
        {
        }

        private $id;
        private $name;

        //Auxiliar field used on the front-end
        private $action = self::ACTION_COMPARE; //default value

        //Default action values;
        private const ACTION_INSERT = "insert";
        private const ACTION_DELETE = "delete";
        private const ACTION_UPDATE = "update";
        //force the business layer to compera the data in the database to decide update or not
        private const ACTION_COMPARE = "compare";

        private const INVALID_ACTION_VALUE = "Only insert, update or delete value can be assigned as action.";

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setId($value)
        {
            $this->id = $value;
        }

        public function setName($value)
        {
            $this->name = $value;
        }

        public function getAction()
        {
            return $this->action;
        }

        public function setAction($value)
        {
            switch ($value) {
                case self::ACTION_INSERT:
                    $this->action = self::ACTION_INSERT;
                    break;
                case self::ACTION_DELETE:
                    $this->action = self::ACTION_DELETE;
                    break;
                case self::ACTION_UPDATE:
                    $this->action = self::ACTION_UPDATE;
                    break;
                default:
                    throw new Exception(self::INVALID_ACTION_VALUE);
            }

        }

        public function jsonSerialize()
        {

            $json = [];
            if (!empty($this->getId())) {
                $json["id"] = $this->getId();
            }

            if (!empty($this->getName())) {
                $json["name"] = $this->getName();
            }

            return $json;

        }

        //helper functions to instruct business layer about this data operation
        public function isDataToDelete()
        {
            return $this->getAction() === self::ACTION_DELETE ? true : false;
        }

        //not implemented yet
        public function isDataToUpdate()
        {
            return $this->getAction() === self::ACTION_UPDATE ? true : false;
        }

        public function isDataToCompare()
        {
            return $this->getAction() === self::ACTION_COMPARE ? true : false;
        }

        public function isDataToInsert()
        {
            return $this->getAction() === self::ACTION_INSERT ? true : false;
        }

    }
}
