<?php

class Home extends KL_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->loadview();
    }
    
    public function about(){
        $this->loadview('home/about');
    }
    
}

