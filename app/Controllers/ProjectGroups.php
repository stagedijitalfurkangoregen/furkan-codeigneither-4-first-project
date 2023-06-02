<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectGroupsModel;

class ProjectGroups extends BaseController
{
    public function __construct()
    {
        $this->pgGroupModel = new ProjectGroupsModel();
        $this->data =  [];
        $this->title =  null;
        $this->description =  null;
        $this->file =  null;
    }
    public function index()
    {
        if($this->request->getPost("pg_group_title")) {
            $this->title = $this->request->getPost("pg_group_title");
            $this->description = $this->request->getPost("pg_group_description");
            $this->file = $this->request->getFile("pg_group_image");
            $this->data =  [
                "pg_group_title"=>$this->title,
                "pg_group_description" =>$this->description,
                "pg_group_image" => $this->file->getName(),
            ];
            $this->file->move("uploads/pg_groups");
            $this->pgGroupModel->insert($this->data);

        }

        if($this->request->getPost("pg_group_edit_title")) {
            $this->title = $this->request->getPost("pg_group_edit_title");
            $this->description = $this->request->getPost("pg_group_edit_description");
            $this->file = $this->request->getFile("pg_group_edit_image");
            if ($this->file->isValid()) {
                $this->data = [
                    "pg_group_title" => $this->title,
                    "pg_group_description" => $this->description,
                    "pg_group_image" => $this->file->getName(),
                ];
                $this->file->move('uploads/pg_groups');
            }
            else{
                $this->data = [
                    "pg_group_title" => $this->title,
                    "pg_group_description" => $this->description,
                ];
            }
            $this->pgGroupModel->update($this->request->getPost("pg_group_edit_id"),$this->data);

        }
        if($this->request->getPost("pg_group_delete_id")) {
            $this->pgGroupModel->where('pg_group_id',$this->request->getPost("pg_group_delete_id"))->delete();
        }

        $pg_groups["pg_groups"] = $this->pgGroupModel->findAll();
        return $this->twig->render('backend/pg_groups/pg_groups',$pg_groups);
    }

    public function pg_group_details($id){


        $pg_group["pg_group"] = $this->pgGroupModel->find($id);
        return $this->twig->render('backend/pg_groups/pg_group_details',$pg_group);

    }
}
