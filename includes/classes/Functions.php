<?php
    class Functions {
        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods removeFromQueryString(), goToPage() and reloadPage() instead.");
        }

        static public function removeFromQueryString($param){
            // Storing the current QueryString (from the URL) as an array
            // of name value pairs, by seperating the string at each "&"
            $queryArray = explode("&", $_SERVER["QUERY_STRING"]);

            // Looping through each of the name=value pairs from the query string
            foreach($queryArray as $key => $query){
                // If the parameter passed to the function is contained within the
                // name=value pair, then remove it from the array of name=value pairs
                if(strpos($query, $param) == 0){
                    array_splice($queryArray, $key, 1);
                }
            }

            // Rebuild the array of name=value pairs into a string, seperated by "&"
            $rebuiltQueryString = implode("&", $queryArray);

            // Using this class's goToPage() method, to redirect the user to the page.php page,
            // along with the new query string (so the parameter that was requested to be
            // removed with be fully removed from the request URL)
            self::goToPage("page.php?" . $rebuiltQueryString);
        }

        // Setting the header of the response to the value passed to the function
        // Killing the current request, to force the page to reload
        static public function goToPage($page){
            header("Location: " . $page);
            die();
        }

        // Setting the header of the response to refresh
        // Killing the current request, to force the page to reload
        static public function reloadPage() {
            header("Refresh:0");
            die();
        }
    }
?>