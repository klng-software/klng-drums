<?php

class KL_Controller extends CI_Controller {

    private $_h_data = array();
    private $_f_data = array();
    private $_bs_css_path = '';
    private $_bs_js_path = '';

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('CorePages_model');
        $this->_init();
    }

    protected function _init() {

        // Exermine Base Urls
        $this->_bs_css_path = base_url("bootstrap/css/bootstrap.min.css");
        $this->_bs_js_path = base_url("bootstrap/js/bootstrap.min.js");

        // Setting header data...
        $this->_h_data['bs_css'] = $this->_bs_css_path;
        $this->_h_data['bs_js'] = $this->_bs_js_path;

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
                        'head_title'=>'KLngDrums'
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
        $result = $this->CorePages_model->get_menu_items();
        
        $data = array();
        $data['menu_items'] = $result;
        return $data;
        
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
