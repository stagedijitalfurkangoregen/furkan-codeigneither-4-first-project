<?php

namespace App\Controllers\backend;

use App\Controllers\BaseController;
use App\Models\SocialMediaModel;

class SocialMedias extends BaseController
{
    public function __construct()
    {
        $this->smModel = new SocialMediaModel();
        $this->data =  [];
        $this->title =  null;
        $this->url =  null;
        $this->file =  null;
    }
    public function index()
    {
        if($this->request->getPost("social_media_title")) {
            $this->title = $this->request->getPost("social_media_title");
            $this->description = $this->request->getPost("social_media_url");
            $this->file = $this->request->getFile("social_media_icon");
            if ($this->file->isValid()) {
                $this->data = [
                    "social_media_title" => $this->title,
                    "social_media_url" => $this->description,
                    "social_media_icon" => $this->file->getName(),
                ];
            }
            $this->file->move("uploads/socialmedias");
            $this->smModel->insert($this->data);

        }

        if($this->request->getPost("social_media_edit_title")) {
            $this->title = $this->request->getPost("social_media_edit_title");
            $this->description = $this->request->getPost("social_media_edit_url");
            $this->file = $this->request->getFile("social_media_edit_icon");
            if ($this->file->isValid()) {
                $this->data = [
                    "social_media_title" => $this->title,
                    "social_media_url" => $this->description,
                    "social_media_icon" => $this->file->getName(),
                ];
                $this->file->move("uploads/socialmedias");
            }
            else{
                $this->data = [
                    "social_media_title" => $this->title,
                    "social_media_url" => $this->description,
                ];
            }
            $this->smModel->update($this->request->getPost("social_media_edit_id"),$this->data);

        }
        if($this->request->getPost("social_media_delete_id")) {
            $this->smModel->where('social_media_id',$this->request->getPost("social_media_delete_id"))->delete();
        }

        $socialmedias["socialmedias"] = $this->smModel->findAll();
        return $this->twig->render('backend/social_media/social_medias',$socialmedias);

    }

    public function social_media_details($id){


        $socialmedia["socialmedia"] = $this->smModel->find($id);
        return $this->twig->render('backend/social_media/social_media_details',$socialmedia);

    }


}
