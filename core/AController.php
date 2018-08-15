<?php

namespace Core;

abstract class AController{
    abstract protected function __construct(); 
    abstract public function Get($request);
    abstract public function Post($request);
}