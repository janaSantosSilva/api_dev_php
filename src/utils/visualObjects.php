<?php

    abstract class VisualObjects {

        private $attributes;

        public function __construct() {}

        public function __set( $attribute, $value ) {
            $this->attributes[$attribute] = $value;
            return $this;
        } 

        public function __get( $attribute ) {
            return $this->attributes[$attribute];
        }

        public function __isset( $attribute ) {
            return isset( $this->attributes[$attribute] );
        }

        public function toArray() {
            return $this->attributes;
        }

        public function fromArray(array $array) {
            $this->attributes = $array;
        }

        public function toJson() {
            return json_encode($this->attributes);
        }

        public function fromJson(string $json) {
            $this->attributes = json_decode($json);
        }
        
    }