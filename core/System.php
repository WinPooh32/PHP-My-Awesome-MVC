<?php

namespace Core;

class System {
    
    const APP_DIR = 'app/';
    const CONTROLLERS_DIR = 'app/Controllers/';
    const MODELS_DIR = 'app/Models/';
    const VIEWS_DIR = 'views/';
    const ROUTES_DIR = 'routes/';
    const CONFIG_DIR = 'config/';
    
    private static $_root_dir = "";
    private static $_settings = NULL;
    
    
    public static function init($root_dir) {
        set_exception_handler('Core\System::handleException');
        
        mb_internal_encoding('UTF-8');
        
        self::$_root_dir = $root_dir;
        
        self::requireSettings();
        self::requireDir(self::getRoot() . System::ROUTES_DIR);

        Router::init();
        Database::init();
    }

    public static function serve(){       
       $input_route = Http::filterRoute();
       
       if(Router::isValid($input_route)){
           self::renderPage($input_route);
       }else{
           self::renderPage('/404');
       }       
    }
    
    public static function renderPage($route){
        $http_server = Http::filterServer();
        $request_method = strtolower($http_server["REQUEST_METHOD"]);
        
        $controller = Router::MakeController($route, $request_method);
            
        switch ($request_method) {
            case "get":
                echo $controller->Get(Http::filterGet());
                break;
            
            case "post":
                $controller->Post(Http::filterPost());
                break;

            default:
                break;
        }
    }
    
    public static function getSettings($settings){
        return self::$_settings[$settings];
    }
    
    public static function getRoot(){
        return self::$_root_dir;
    }
    
    public static function handleException($exception){
        echo "<br>",$exception->getMessage(),"<br>Backtrace:<br>";
        echo str_replace("\n", "\n<br>", $exception->getTraceAsString());
        die();
    }
    
    public static function requireDir($path){
        $pattern = $path . "/*.php";

        $sources = glob($pattern, GLOB_NOSORT);

        foreach ($sources as $filename) {
            require_once $filename;
        }
    }
    
    private static function requireSettings(){
        $pattern =  self::$_root_dir . self::CONFIG_DIR . "/*.php";

        $sources = glob($pattern, GLOB_NOSORT);

        foreach ($sources as $filename) {
            $base = explode('.', basename($filename))[0];
            $ret = require $filename;
            self::$_settings[$base] = $ret;
        }
    }

    private function __construct() {}
    private function __clone() {}
}