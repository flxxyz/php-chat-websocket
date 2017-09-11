<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Identicon\Identicon;

class Home extends CI_Controller
{
    public function index ()
    {
        if(isset($this->session->id) and
            isset($this->session->name) and
            isset($this->session->sex) and
            isset($this->session->icon)) {
            header('location: ' . site_url() . '?c=home&m=room');
        }

        $this->load->view('Chat/index', ['action' => site_url() . '?c=home&m=login',]);
    }

    public function login ()
    {
        $this->session->id = mt_rand(1000, 1999);
        $this->session->name = $this->input->get('username');
        $this->session->sex = $this->input->get('sex');

        $identicon = new Identicon();
        //$identicon->displayImage($this->session->name);
        //$imageData = $identicon->getImageData('bar');
        $imageDataUri = $identicon->getImageDataUri($this->session->name);

        $this->session->icon = $imageDataUri;

        header('location: ' . site_url() . '?c=home&m=room');
    }

    public function logout()
    {
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('sex');
        $this->session->unset_userdata('icon');

        header('location: ' . site_url() . '?c=home&m=index');
    }

    public function room ()
    {
        if(!( isset($this->session->id) and
            isset($this->session->name) and
            isset($this->session->sex) and
            isset($this->session->icon) )) {
            header('location: ' . site_url() . '?c=home&m=index');
        }

        $sess = $this->session;
        $this->load->view('Chat/room', [
            'sess' => [
                'id' => $sess->id,
                'name' => $sess->name,
                'sex' => $sess->sex,
                'icon' => $sess->icon,
                ],
            ]);
    }
}