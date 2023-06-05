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
            return redirect()->to(base_url('management'));
        }
        $admin["admin"] =  $this->usersModel->where('user_id', $_SESSION["ykprOwSuperUser"]["user_id"])->first();
        if($this->request->getPost("user_name")) {
            $this->title = $this->request->getPost("user_name");
            $this->description = $this->request->getPost("user_pass");
            $this->auth = $this->request->getPost("user_auth");
            $this->data = [
                "user_name" => $this->title,
                "user_password" => $this->description,
                "user_auth" => $this->auth,
            ];
            $this->usersModel->insert($this->data);

        }
        if($this->request->getPost("admin_edit_name") && $this->request->getPost("user_edit_name") !== $admin["admin"]["user_name"] ) {
            $this->title = $this->request->getPost("admin_edit_name");
            $this->description = $this->request->getPost("admin_edit_pass");
            $this->data = [
                "user_name" => $this->title,
                "user_password" => $this->description,
            ];
            $this->usersModel->update($this->request->getPost("admin_edit_id"),$this->data);

        }
        if($this->request->getPost("user_edit_name")) {
            $this->title = $this->request->getPost("user_edit_name");
            $this->description = $this->request->getPost("user_edit_pass");
            $this->auth = $this->request->getPost("user_edit_auth");
            $this->data = [
                "user_name" => $this->title,
                "user_password" => $this->description,
                "user_auth" => $this->auth,
            ];
            $this->usersModel->update($this->request->getPost("user_edit_id"),$this->data);

        }

        if($this->request->getPost("user_delete_id")) {
            $this->usersModel->where('user_id',$this->request->getPost("user_delete_id"))->delete();
        }

        $admin["users"] =  $this->usersModel->where('user_id !=', $admin["admin"]["user_id"])->findAll();

        return $this->twig->render('backend/admin',$admin);
    }

    public function admin_details($id){

        $user["user"] = $this->usersModel->find($id);
        return $this->twig->render('backend/admin_details',$user);

    }


}