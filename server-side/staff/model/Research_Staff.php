<?php


namespace staff\model;
use core\data\PDOData;
require_once("core/data/PDOData.php");


class Research_Staff{
    //Khởi tạo DB
    public function __contruct(){
    }

    //Lấy tất cả thông tin từ bảng research_staff
    public function getAll() {
        $db = new PDOData();
        $ret = $db->doQuery("select * from research_staff");
        $db->__destruct();
        return $ret;
    }

    //Lấy id và tên của research từ hai bảng research_staff và research dựa trên staff_id
    public function getResearchID($staff_id) {
        $db = new PDOData();
        $ret = $db->doQuery("select research.research_id, name from research_staff inner join research on research_staff.research_id = research.research_id where staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Xóa các lĩnh vực quan tâm của 1 staff
    public function deleteRelation($staff_id) {
        $db = new PDOData();
        $ret = $db->doSql("delete from research_staff where staff_id = '$staff_id'");
        $db->__destruct();
        return $ret;
    }

    //Thêm các lĩnh vực quan tâm của 1 staff
    public function createRelation($research_id, $staff_id) {
        $db = new PDOData();
        $ret = $db->doSql("insert into research_staff (research_id, staff_id)  values ('$research_id','$staff_id')");
        $db->__destruct();
        return $ret;
    }
}