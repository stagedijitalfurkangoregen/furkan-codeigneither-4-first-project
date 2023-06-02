<?php

namespace App\Controllers\backend;

use App\Controllers\BaseController;
use App\Models\ReferencesModel;

class References extends BaseController
{
    public function __construct()
    {
        $this->referenceModel = new ReferencesModel();
        $this->data =  [];
        $this->title =  null;
        $this->description =  null;
        $this->file =  null;
    }
    public function index()
    {
        if($this->request->getPost("reference_title")) {
            $this->title = $this->request->getPost("reference_title");
            $this->description = $this->request->getPost("reference_description");
            $this->file = $this->request->getFile("reference_image");
            if ($this->file->isValid()) {
                $this->data = [
                    "reference_title" => $this->title,
                    "reference_description" => $this->description,
                    "reference_image" => $this->file->getName(),
                ];
            }
            $this->file->move("uploads/references");
            $this->referenceModel->insert($this->data);

        }

        if($this->request->getPost("reference_edit_title")) {
            $this->title = $this->request->getPost("reference_edit_title");
            $this->description = $this->request->getPost("reference_edit_description");
            $this->file = $this->request->getFile("reference_edit_image");
            if ($this->file->isValid()) {
                $this->data = [
                    "reference_title" => $this->title,
                    "reference_description" => $this->description,
                    "reference_image" => $this->file->getName(),
                ];
                $this->file->move("uploads/references");
            }
            else{
                $this->data = [
                    "reference_title" => $this->title,
                    "reference_description" => $this->description,
                ];
            }

            $this->referenceModel->update($this->request->getPost("reference_edit_id"),$this->data);

        }
        if($this->request->getPost("reference_delete_id")) {
            $this->referenceModel->where('reference_id',$this->request->getPost("reference_delete_id"))->delete();
        }

        $references["references"] = $this->referenceModel->findAll();
        return $this->twig->render('backend/references/references',$references);
    }

    public function reference_details($id){


        $reference["reference"] = $this->referenceModel->find($id);
        return $this->twig->render('backend/references/reference_details',$reference);

    }
}
