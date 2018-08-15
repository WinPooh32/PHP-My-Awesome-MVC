<?php

namespace App\Controllers;

use Core\AController,
    Core\View;

class Info extends AController {
    public function __construct() {
        
    }
    
    public function Get($request){
        $data = [];
        $data['content'] = (new View("info", []))->render();

        $ret = (new View("layout.default", $data))->render();
        
        return $ret;
    }
    
    public function Post($request){
        
    }
}