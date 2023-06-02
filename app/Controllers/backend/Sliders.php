<?php

namespace App\Controllers\backend;

use App\Controllers\BaseController;
use App\Models\SlidersModel;

class Sliders extends BaseController
{
    public function __construct()
    {
        $this->sliderModel = new SlidersModel();
        $this->data =  [];
        $this->title =  null;
        $this->description =  null;
        $this->file =  null;
    }
    public function index()
    {
        if($this->request->getPost("slider_title")) {
            $this->title = $this->request->getPost("slider_title");
            $this->description = $this->request->getPost("slider_description");
            $this->file = $this->request->getFile("slider_image");
            if ($this->file->isValid()) {
                $this->data = [
                    "slider_title" => $this->title,
                    "slider_description" => $this->description,
                    "slider_image" => $this->file->getName(),
                ];
            }
            $this->file->move("uploads/sliders");
            $this->sliderModel->insert($this->data);

        }

        if($this->request->getPost("slider_edit_title")) {
            $this->title = $this->request->getPost("slider_edit_title");
            $this->description = $this->request->getPost("slider_edit_description");
            $this->file = $this->request->getFile("slider_edit_image");
            if ($this->file->isValid()) {
                $this->data = [
                    "slider_title" => $this->title,
                    "slider_description" => $this->description,
                    "slider_image" => $this->file->getName(),
                ];
                $this->file->move("uploads/sliders");
            }
            else{
                $this->data = [
                    "slider_title" => $this->title,
                    "slider_description" => $this->description,
                ];
            }
            $this->sliderModel->update($this->request->getPost("slider_edit_id"),$this->data);

        }
        if($this->request->getPost("slider_delete_id")) {
            $this->sliderModel->where('slider_id',$this->request->getPost("slider_delete_id"))->delete();
        }

        $sliders["sliders"] = $this->sliderModel->findAll();
        return $this->twig->render('backend/sliders/sliders',$sliders);
    }

    public function slider_details($id){


        $slider["slider"] = $this->sliderModel->find($id);
        return $this->twig->render('backend/sliders/slider_details',$slider);

    }
}
