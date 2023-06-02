<?php

namespace App\Controllers;

use App\Models\BlogModel;
use App\Models\ContactModel;
use App\Models\ProjectsModel;
use App\Models\ReferencesModel;
use App\Models\SlidersModel;

class Home extends BaseController
{
    public function __construct()
    {
        $this->blogModel = new BlogModel();
        $this->contactModel = new ContactModel();
        $this->projectModel = new ProjectsModel();
        $this->referenceModel = new ReferencesModel();
        $this->sliderModel = new SlidersModel();
        $this->data =  [];

    }

    public function index()
    {
        $this->data["blogs"] =  $this->blogModel->findAll();
        $this->data["contacts"] =  $this->contactModel->findAll();
        $this->data["projects"] =  $this->projectModel->findAll();
        $this->data["references"] =  $this->referenceModel->findAll();
        $this->data["sliders"] =  $this->sliderModel->findAll();

        return $this->twig->render('frontend/home',$this->data);
    }

    public function category($i){
        $data['number'] = $i;
        return view('category',$data);
    }
}
