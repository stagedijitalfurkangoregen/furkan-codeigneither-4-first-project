<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Auth extends BaseController
{

    public function __construct()
    {
        $this->usersModel = new UsersModel();

    }

    public function index(){
        $auth = ["user_name"=>"","user_password"=>""];
        if($this->request->getPost()) {
            $username = $this->request->getPost("user_name");
            $password = $this->request->getPost("user_password");

            if($this->usersModel->where('user_name',$username)->first()) {
                $auth = $this->usersModel->where('user_name', $username)->first();
            }
            if($auth['user_password'] === $password){
                $this->session->set('ykprOwSuperUser',$auth);
            }
            return redirect()->to(base_url('management/admin'));

        }
        return $this->twig->render('backend/login',$auth);
    }


    public function admin(){

        if($this->request->getPost("value")) {
            $this->session->remove('ykprOwSuperUser');
            return redirect()->to(base_url('management/login'));
        }

        return $this->twig->render('backend/admin',[e]);
    }


}