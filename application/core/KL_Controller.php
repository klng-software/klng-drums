<?php

class KL_Controller extends CI_Controller {

    private $_h_data = array();
    private $_f_data = array();
    private $_csm_css_path = '';
    private $_bs_css_path = '';
    private $_bs_js_path = '';
    private $_jq_js_path = '';
    private $_csm_js_path = '';
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('CorePages_model');
        $this->_init();
        
    }

    protected function _init() {
        
        $this->_loadSessionData();
        
        // Load language files...
        $this->lang->load('main_lang', $this->session->userdata('language'));
        
//        die("<pre>" . print_r($this->session->userdata(), true) . "</pre>");
        // Exermine Base Urls
        $this->_bs_css_path = base_url("bootstrap/css/bootstrap.min.css");
        $this->_csm_css_path = base_url("style/custom.css");
        $this->_bs_js_path = base_url("bootstrap/js/bootstrap.min.js");
        $this->_jq_js_path = base_url("jquery/jquery-3.3.1.min.js");
        $this->_csm_js_path = base_url("js/custom.js");
        
        // Setting header data...
        $this->_h_data['bs_css'] = $this->_bs_css_path;
        $this->_h_data['csm_css'] = $this->_csm_css_path;
        $this->_h_data['bs_js'] = $this->_bs_js_path;
        $this->_h_data['jq_js'] = $this->_jq_js_path;
        $this->_h_data['csm_js'] = $this->_csm_js_path;

        // Setting footer data...
        $this->_f_data['copy_owner'] = 'Kris Lange';
        $this->_f_data['copy_year'] = date('Y');
    }

    private function _prepareMainData($resource = array(), $page_data = array()) {
        
        // Header...
        $header_data = $this->_h_data;
        if(!array_key_exists('title', $header_data)){
            $header_data['title'] = ucfirst($resource['module']);
        } else {
            $header_data['title'] = $page_data['title'];
        }
        
        // Head...
        $head_data = array(
                        'head_title'=>'KLngDrums',
                        'slider1_url'=> site_url('img/slider1_mid.jpg'),
                        'slider2_url'=> site_url('img/slider2_mid.jpg'),
                        'slider3_url'=> site_url('img/slider3_mid.jpg')
                    );
        
        // Menu...
        $menu_data = $this->_getMenuData();
        
        // Footer...
        $footer_data = array_merge($this->_f_data);

        //Page...
        $page = "{$resource['module']}/{$resource['page']}";
        
        
        
        
        return $this->_loadAreas($header_data, $head_data, $menu_data, $footer_data, $page, $page_data);
        
    }

    private function _loadAreas($header_data, $head_data, $menu_data, $footer_data, $page, $page_data){
        // Loading views an returning them as html...
        $html_header = $this->load->view('templates/header', $header_data, true);
        $html_head = $this->load->view('templates/head', $head_data, true);
        $html_menu = $this->load->view('templates/menu', $menu_data, true);
        $html_footer = $this->load->view('templates/footer', $footer_data, true);
        $html_content = $this->load->view($page, $page_data, true);
        
        
        $main_page_data = array(
            'header' => $html_header,
            'head' => $html_head,
            'menu' => $html_menu,
            'footer' => $html_footer,
            'content' => $html_content
        );
        return $main_page_data;
    }
    
    protected function _identifyResource($page) {

        $search = explode('/', $page);
        switch (count($search)) {
            case 2:
                $module = $search[0];
                $page = $search[1];
                break;
            case 1:
                $module = $search[0];
            default:
                $page = 'index';
                break;
        }
        $data = array('module' => $module, 'page' => $page);

        return $data;
    }

    private function _getMenuData(){
//        $this->lang->load('main_lang', $this->session->userdata('language'));
        $result = $this->CorePages_model->get_menu_items();
        $data = array();
        $data['menu_items'] = array();
        $i = 0;
        foreach($result as $el){
            $el['page_name'] = $this->lang->line($el['page_name']);
            $data['menu_items'][$i] = array();
            $data['menu_items'][$i] = array_merge($data['menu_items'][$i], $el);
            $i++;
        }
        
        return $data;
        
    }
    
    private function _loadSessionData(){
        //Set prefered Languages
        $str_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $str_lang_short = substr($str_accept_language, 0, 2);
        switch($str_lang_short) {
            case 'en':
                $this->session->set_userdata('language', 'english');
                break;
            case 'de':
                $this->session->set_userdata('language', 'german');
                break;
        }
        
    }
    
    public function loadview($page = 'home/index', $serialized = '') {

        $resource = $this->_identifyResource($page);

        if (!file_exists(APPPATH . "views/{$resource['module']}/{$resource['page']}.php")) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        $page_data = unserialize($serialized);
        if (!is_array($page_data)) {
            $page_data = array();
        }
        if (count($page_data) == 0) {
            $page_data['title'] = ucfirst($resource['page']); // Capitalize the first letter
        }

        $main_page_data = $this->_prepareMainData($resource, $page_data);
        // Loading view...
        $this->load->view('templates/main', $main_page_data);
    }

}
