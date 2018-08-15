<?php

namespace Core;

class View{

    private $_view_path = NULL;
    private $_data = NULL;

    public function __construct($view, $data = []) {
        $this->_view_path = $this->getView($view);
        $this->_data = $data;
    }
    
    public function render(){
        if($this->_view_path == NULL) {
            return;
        }
        
        $rendered;
        
        //avoid "$this->" from template code
        $data = $this->_data;
        
        //inject template
        ob_start();
        
        require $this->_view_path;
        $rendered = ob_get_contents();
        
        ob_end_clean();
        
        return $rendered;
    }

    private function getView($view) {
        return System::getRoot() . System::VIEWS_DIR . $view . '.phtml';
    }
}