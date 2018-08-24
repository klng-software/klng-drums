<?php

class Home extends KL_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $data = array();
//        $data = array('slider1_url'=> site_url('img/slider1.jpg'),
//                      'slider2_url'=> site_url('img/slider2.jpg'),
//                      'slider3_url'=> site_url('img/slider3.jpg')
//                    );
        
        
        $serialized = serialize($data);
        
        $this->loadview('home/index', $serialized);
    }
    
    public function about(){
        $this->loadview('home/about');
    }
    
}

