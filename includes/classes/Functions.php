<?php
    class Functions {
        static public function removeFromQueryString($queryString, $param, $page){
            $queryArray = explode("&", $queryString);
            foreach($queryArray as $key => $query){
                if(strpos($query, $param) > -1){
                    array_splice($queryArray, $key, 1);
                }
            }
            $rebuiltQueryString = implode($queryArray);
            header("Location: " . $page . "?" . $rebuiltQueryString);
            die();
        }
    }
?>