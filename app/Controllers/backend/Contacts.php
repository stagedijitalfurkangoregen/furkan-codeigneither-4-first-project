<?php

namespace App\Controllers\backend;

use App\Controllers\BaseController;
use App\Models\ContactModel;

class Contacts extends BaseController
{
    public function __construct()
    {
        $this->contactModel = new ContactModel();
        $this->data =  [];
        $this->title = null;
        $this->admin = null;
        $this->mail = null;
        $this->phone = null;
        $this->mobile = null;
        $this->address = null;
        $this->map = null;
        $this->data = null;
    }



    public function index(){

        if($this->request->getPost("contact_title")) {
            $this->title = $this->request->getPost("contact_title");
            $this->admin = $this->request->getPost("contact_admin");
            $this->mail = $this->request->getPost("contact_mail");
            $this->phone = $this->request->getPost("contact_phone");
            $this->mobile = $this->request->getPost("contact_mobile");
            $this->address = $this->request->getPost("contact_address");
            $this->map = $this->request->getPost("contact_map");
            $this->data = [
                "contact_title" => $this->title,
                "contact_admin" => $this->admin,
                "contact_mail" => $this->mail,
                "contact_phone" => $this->phone,
                "contact_mobile" => $this->mobile,
                "contact_address" => $this->address,
                "contact_map" => $this->map,
            ];

            $this->contactModel->insert($this->data);

        }

        if($this->request->getPost("contact_edit_title")) {
            $this->title = $this->request->getPost("contact_edit_title");
            $this->admin = $this->request->getPost("contact_edit_admin");
            $this->mail = $this->request->getPost("contact_edit_mail");
            $this->phone = $this->request->getPost("contact_edit_phone");
            $this->mobile = $this->request->getPost("contact_edit_mobile");
            $this->address = $this->request->getPost("contact_edit_address");
            $this->map = $this->request->getPost("contact_edit_map");

            $this->data = [
                "contact_title" => $this->title,
                "contact_admin" => $this->admin,
                "contact_mail" => $this->mail,
                "contact_phone" => $this->phone,
                "contact_mobile" => $this->mobile,
                "contact_address" => $this->address,
                "contact_map" => $this->map,
            ];

            $this->contactModel->update($this->request->getPost("contact_edit_id"),$this->data);

        }
        if($this->request->getPost("contact_delete_id")) {
            $this->contactModel->where('contact_id',$this->request->getPost("contact_delete_id"))->delete();
        }

        $contacts["contacts"] = $this->contactModel->findAll();
        return $this->twig->render('backend/contacts/contacts',$contacts);
    }


    public function contact_details($id){


        $contact["contact"] = $this->contactModel->find($id);
        return $this->twig->render('backend/contacts/contact_details',$contact);

    }

}
