<?php

namespace App\Controllers\backend;

use App\Controllers\BaseController;
use App\Models\ProjectGroupsModel;
use App\Models\ProjectsModel;

class Projects extends BaseController
{

    public function __construct()
    {
        $this->projectModel = new ProjectsModel();
        $this->pg_groups = new ProjectGroupsModel();
        $this->data =  [];
        $this->title =  null;
        $this->file =  null;
        $this->description =  null;
        $this->pggroup =  null;
    }
    public function index()
    {
        if($this->request->getPost("project_title")) {
            $this->title = $this->request->getPost("project_title");
            $this->description = $this->request->getPost("project_description");
            $this->pggroup = $this->request->getPost("pg_group");
            $this->file = $this->request->getFile("image");

            if ($this->file->isValid()){
                $this->data =  [
                    "project_title"=>$this->title,
                    "project_description" =>$this->description,
                    "pg_group_id" =>$this->pggroup,
                    "project_image" =>$this->file->getName(),
                ];
            }

            $this->file->move("uploads/projects");
            $this->projectModel->insert($this->data);

        }

        if($this->request->getPost("project_edit_title")) {
            $this->title = $this->request->getPost("project_edit_title");
            $this->description = $this->request->getPost("project_edit_description");
            $this->file = $this->request->getFile("project_edit_image");
            $this->pggroup = $this->request->getPost("edit_pg_group");
            if ($this->file->isValid()) {
                $this->data = [
                    "project_title" => $this->title,
                    "project_description" => $this->description,
                    "pg_group_id" =>$this->pggroup,
                    "project_image" =>$this->file->getName(),
                ];
                $this->file->move("uploads/projects");
            }
            else{
                $this->data = [
                    "project_title" => $this->title,
                    "project_description" => $this->description,
                    "pg_group_id" =>$this->pggroup,
                ];
            }
            $this->projectModel->update($this->request->getPost("project_edit_id"),$this->data);

        }
        if($this->request->getPost("project_delete_id")) {
            $this->projectModel->where('project_id',$this->request->getPost("project_delete_id"))->delete();
        }
        $projects["pggroups"] = $this->pg_groups->findAll();
        $projects["projects"] = $this->projectModel->findAll();
        return $this->twig->render('backend/projects/projects',$projects);
    }

    public function project_details($id){

        $project["pggroups"] = $this->pg_groups->findAll();
        $project["project"] = $this->projectModel->find($id);
        return $this->twig->render('backend/projects/project_details',$project);

    }


}
