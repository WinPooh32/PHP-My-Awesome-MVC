<?php

namespace Core;

class Http {
    
    private static $_parsedServer = NULL;
    private static $_parsedGet = NULL;
    private static $_parsedPost = NULL;
    private static $_parsedRoute = NULL;
    
    public static function setCookies(){
        
    }
    
    public static function getCookies(){
        
    }
    
    public static function redirect($route){
        //exclude query
        $clean_route =  mb_split("\?", $route)[0];
        
        if(Router::isValid($clean_route)){
            header('Location: ' . $route);
        }else{
            throw new Exception("Route \"" . $route . "\" is not defined.");
        }     
    }
    
    public static function filterRoute(){
        if(self::$_parsedRoute == NULL){
            self::$_parsedRoute = "";
            
            $http_server = self::filterServer();
            
            //exclude ? queries
            $tmp = mb_strtolower(explode("?", $http_server["REQUEST_URI"])[0]);
            
            //remove / dublicates
            foreach(explode("/", $tmp) as $part){
                if($part != ""){
                    self::$_parsedRoute .= "/".$part;
                }
            }
            
            if(self::$_parsedRoute == ""){
                self::$_parsedRoute = "/";
            }
            
            $_SERVER["REQUEST_URI"] = self::$_parsedRoute;
        }
        
        return self::$_parsedRoute;
    }
    
    public static function filterServer(){
        if(self::$_parsedPost == NULL) {
            self::parseServer();
        }
        return self::$_parsedServer;
    }
    
    public static function filterGet(){
        if(self::$_parsedGet == NULL){
             self::parseGet();
        }
        return self::$_parsedGet;
    }
    
    public static function filterPost(){
        if(self::$_parsedPost == NULL){
            self::parsePost();
        }
        
        return self::$_parsedPost;
    }
    
    private static function parseServer(){
        $args = [
            'SERVER_ADDR' => FILTER_VALIDATE_IP,
            'SERVER_NAME' => FILTER_VALIDATE_DOMAIN,
            'REQUEST_METHOD' => FILTER_UNSAFE_RAW,
            'HTTP_ACCEPT' => FILTER_UNSAFE_RAW,
            'HTTP_ACCEPT_CHARSET' => FILTER_UNSAFE_RAW ,
            'HTTP_ACCEPT_ENCODING' => FILTER_UNSAFE_RAW,
            'HTTP_ACCEPT_LANGUAGE' => FILTER_UNSAFE_RAW,
            'QUERY_STRING' => FILTER_SANITIZE_SPECIAL_CHARS, 
            'REMOTE_ADDR' => FILTER_VALIDATE_IP,
            'REMOTE_HOST' => FILTER_VALIDATE_DOMAIN,
            'REQUEST_URI' => FILTER_SANITIZE_SPECIAL_CHARS,
            'PATH_INFO' => FILTER_SANITIZE_ENCODED,
            'ORIG_PATH_INFO' => FILTER_SANITIZE_ENCODED
        ];
        
        self::$_parsedServer = filter_input_array(INPUT_SERVER, $args);
    }
    
    private static function parseGet(){
        $filter = FILTER_SANITIZE_SPECIAL_CHARS;
        $flags = [];
        
        self::$_parsedGet = self::filter_input_array_with_default_flags(INPUT_GET, $filter, $flags);
    }
    
    private static function parsePost(){
        $filter = FILTER_SANITIZE_SPECIAL_CHARS;
        $flags = [];
        
        self::$_parsedPost = self::filter_input_array_with_default_flags(INPUT_POST, $filter, $flags);
    }
    
    //https://php.net/manual/ru/function.filter-input-array.php#114182
    function filter_input_array_with_default_flags($type, $filter, $flags, $add_empty = true) {
        $loopThrough = array();
        switch ($type) {
            case INPUT_GET : $loopThrough = $_GET;
                break;
            case INPUT_POST : $loopThrough = $_POST;
                break;
            case INPUT_COOKIE : $loopThrough = $_COOKIE;
                break;
            case INPUT_SERVER : $loopThrough = $_SERVER;
                break;
            case INPUT_ENV : $loopThrough = $_ENV;
                break;
        }

        $args = array();
        foreach ($loopThrough as $key => $value) {
            $args[$key] = array('filter' => $filter, 'flags' => $flags);
        }

        return filter_input_array($type, $args, $add_empty);
    }

    private function __construct() {}
    private function __clone() {}
}
