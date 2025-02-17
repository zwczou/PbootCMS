<?php


namespace app\admin\model\content;

use core\basic\Model;

class ReportModel  extends Model
{
    public function getList($page = true)
    {
        return parent::table('ay_report')->order('id desc')->page()->select();
    }

    public function getReport($id)
    {
        return parent::table('ay_report')->where("id=$id")->find();
    }


    public function findReport($field, $keyword)
    {
        return parent::table('ay_report')->like($field, $keyword)->order('id desc')->page()->select();
        
    }

    public function addReport(array $data)
    {
        return parent::table('ay_report')->autoTime()->insert($data);
    }

}