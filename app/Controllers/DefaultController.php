<?php

namespace App\Controllers;

use Core\AController,
    Core\View,
    App\Models\Item,
    App\Models\SearchMap;

class DefaultController extends AController {
    public function __construct() {    
    }

    public function Get($request){
        $version = "1.0";

        if(isset($request["version"])){
            $version = $request["version"];
        }
        
        $data = [
            "title" => "Hello, title!",
            "content" => (new View("default",  ["version" => $version]))->render()
        ];

        return (new View("layout.default", $data))->render();
    }
    
    public function Post($request){
        
    }
}
