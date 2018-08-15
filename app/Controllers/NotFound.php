<?php

namespace App\Controllers;

use Core\AController,
    Core\View;

class NotFound extends AController {
    public function __construct() {
        
    }
    
    public function Get($request){
        header('HTTP/1.0 404 Not Found');
        
        $data = [
            "title" => "404 - Page not found!",
            "head" => "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/404.css\"/>",
            "content" => (new View("404", []))->render()
        ];

        return (new View("layout.default", $data))->render();
    }
    
    public function Post($request){
        
    }
}