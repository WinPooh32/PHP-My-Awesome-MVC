<?php

namespace Core;

class Router {
    
    private static $_get_routes = array();
    private static $_post_routes = array();
    
    public static function init(){    
        //Load routers scripts
        System::requireDir(System::getRoot() . System::ROUTES_DIR);
    }
    
    public static function isValid($route){
        return array_key_exists($route, Router::$_get_routes) 
                || array_key_exists($route, Router::$_post_routes);
    }
    
    public static function Get($route, $controller){
        Router::$_get_routes[strtolower($route)] = $controller;
    }
    
    public static function Post($route, $controller){
        Router::$_post_routes[strtolower($route)] = $controller;
    }
    
    public  static function MakeController($input_route, $request_method){    
        switch ($request_method) {
            case "get":
                return new self::$_get_routes[$input_route]();
                
            case "post":
                return new self::$_post_routes[$input_route]();

            default:
                break;
        }
    }
    
    private function __construct() {}
    protected function __clone() {}
}
