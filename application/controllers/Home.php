<?php

class Home extends KL_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->lang->load('welcome_lang', $this->session->userdata('language'));
    }
    
    public function index(){
        $lang = array();
        $lang['hi_there'] =  $this->lang->line('hi_there');
        $lang['welcome_message'] = $this->lang->line('welcome_message');
        
        $data = array('lang'=>$lang);
        
        $serialized = serialize($data);
        
        $this->loadview('home/index', $serialized);
    }
    
    public function about(){
        $this->loadview('home/about');
    }
    
}

