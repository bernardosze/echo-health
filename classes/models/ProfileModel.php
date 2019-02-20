<?php

namespace classes\models {

    class ProfileModel
    {
        private $id;
        private $name;

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

        public function getName()
        {
            return $this->name;
        }

        public function setName($value)
        {
            $this->name = $value;
        }

    }

}
