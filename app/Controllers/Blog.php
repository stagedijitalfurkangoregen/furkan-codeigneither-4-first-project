<?php

namespace App\Controllers;



use App\Models\BlogModel;

class Blog extends BaseController
{

    public function __construct()
    {
        $this->blogModel = new BlogModel();
        $this->data =  [];
        $this->title =  null;
        $this->description =  null;
    }



public function index(){

        if($this->request->getPost("blog_title")) {
        $this->title = $this->request->getPost("blog_title");
        $this->description = $this->request->getPost("blog_description");
        $this->data =  [
            "blog_title"=>$this->title,
            "blog_description" =>$this->description,
        ];
        $this->blogModel->insert($this->data);

    }

    if($this->request->getPost("blog_edit_title")) {
        $this->title = $this->request->getPost("blog_edit_title");
        $this->description = $this->request->getPost("blog_edit_description");
        $this->data =  [
            "blog_title"=>$this->title,
            "blog_description" =>$this->description,
        ];
        $this->blogModel->update($this->request->getPost("blog_edit_id"),$this->data);

    }
    if($this->request->getPost("blog_delete_id")) {
        $this->blogModel->where('blog_id',$this->request->getPost("blog_delete_id"))->delete();
    }

    $blogs["blogs"] = $this->blogModel->findAll();
    return $this->twig->render('frontend/blog',$blogs);
}


    public function blog_details($id){


        $blog["blog"] = $this->blogModel->find($id);
        return $this->twig->render('frontend/blog_detail',$blog);

    }







}