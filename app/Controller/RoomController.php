<?php

namespace App\Controller;

use Identicon\Identicon;

class RoomController extends Controller
{
    public function index() {
        if(is_null(session()->get('id')) && is_null(session()->get('name')) && is_null(session()->get('sex')) && is_null(session()->get('icon'))) {
                redirect($this->config['url']);
        }
        
        view('index');
    }
    
    public function login() {
        $query = $this->request()->query;
        session()->set(['id' => mt_rand(1000, 1999)]);
        session()->set(['name' => $query['username']]);
        session()->set(['sex' => $query['sex']]);

        $identicon = new Identicon();
        //$identicon->displayImage($this->session->name);
        //$imageData = $identicon->getImageData('bar');
        $imageDataUri = $identicon->getImageDataUri($this->session->name);

        session()->set(['icon' => $imageDataUri]);
        
        redirect($this->config['url'] . 'room');
    }
    
    public function logout() {
        unset($_SESSION['id']);
        unset($_SESSION['name']);
        unset($_SESSION['sex']);
        unset($_SESSION['icon']);

        redirect($this->config['url']);
    }
    
    public function room() {
        if(is_null(session()->get('id')) && is_null(session()->get('name')) && is_null(session()->get('sex')) && is_null(session()->get('icon'))) {
                redirect($this->config['url']);
        }
        
        view('room', [
            'user' => [
                'id' => session()->get('id'),
                'name' => session()->get('name'),
                'sex' => session()->get('sex'),
                'icon' => session()->get('icon'),
                ],
            ]);
    }
}