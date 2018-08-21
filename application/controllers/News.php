<?php

class News extends KL_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
    }

    public function index() {
        $data = array();
        $data['news'] = $this->news_model->get_news();
        $data['title'] = 'News archive';
        $serialized = serialize($data);
        $this->loadview('news/index', $serialized);
    }

    public function view($slug = NULL) {
        $data = array();
        $data['news_item'] = $this->news_model->get_news($slug);

        if (empty($data['news_item'])) {
            show_404();
        }

        $data['title'] = $data['news_item']['title'];
        $serialized = serialize($data);
        $this->loadview('news/view', $serialized);
    }

    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Create a news item';
        $serialized = serialize($data);


        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->loadview('news/create', $serialized);
        } else {
            $this->news_model->set_news();
            $this->loadview('news/success', $serialized);
        }
    }

}
