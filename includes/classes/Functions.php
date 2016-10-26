<?php
    class Functions {
        static public function removeFromQueryString($param){
            $queryArray = explode("&", $_SERVER["QUERY_STRING"]);
            foreach($queryArray as $key => $query){
                if(strpos($query, $param) > -1){
                    array_splice($queryArray, $key, 1);
                }
            }
            $rebuiltQueryString = implode("&", $queryArray);
            self::goToPage("page.php?" . $rebuiltQueryString);
        }

        static public function goToPage($page){
            header("Location: " . $page);
            die();
        }

        static public function reloadPage() {
            header("Refresh:0");
            die();
        }
    }
?>