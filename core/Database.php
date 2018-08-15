<?php

namespace Core;

use ActiveRecord\Config,
    ActiveRecord\Connection;

class Database {
       
    static $ready = false;
    static $ar = NULL;
    
    public static function isReady(){
        return self::$ready;
    }
    
    public static function init(){
        Config::initialize(function($cfg) {
            $info = System::getSettings("database");
            
            $driver = $info["driver"];
            
            $username = $info["user"];
            $password = $info["password"];
            
            $database = $info["database"];
            
            $tcp = $info["tcp"];
            $port = $info["port"];
            $unix_socket = $info["unix_socket"];
            
            $host;
            
            if($unix_socket){
                $host = "unix(".$unix_socket.")";
            }else{
                $host = $tcp.":".$port;
            }
            
            $connection = $driver.'://'.$username.':'.$password.'@'.$host.'/'.$database;
                        
            Connection::$datetime_format = 'Y-m-d H:i:s'; 
            $cfg->set_model_directory(System::getRoot() . System::MODELS_DIR);
            $cfg->set_connections(
                    array(
                        'development' => $connection
                    )
            );
            
            self::$ready = true;
            self::$ar = Config::instance(); 
        });
    }
    
    private final function __construct() {}
}