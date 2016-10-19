<?php
    class Database {
        private static $_database;
        private static $allowCreate = false;
        private $_connection;

        public function __construct(){
            if(!self::$allowCreate){
                throw new error("Cannot use new() constructor on this class. Please use getInstance() to access the singleton instance instead.");
            }
        }

        static public function getInstance(){
            if(!isset(self::$_database)){
                self::$allowCreate = true;
                self::$_database = new Database();
                self::$allowCreate = false;
            }
            return self::$_database;
        }

        public function connect(){
            $this->_connection = "";
        }

        public function addUser($newUser){

        }

        public function removeUser($rmvUser){

        }

    }
?>