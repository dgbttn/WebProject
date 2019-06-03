<?php

namespace topic\model;
use core\data\PDOData;
require_once("core/data/PDOData.php");

class Topic{
    //Khởi tạo DB
    public function __contruct(){
    }

    //Lấy các chủ đề nghiên cứu dựa trên staff_id
    public function getTopicByStaffID($staff_id) {
        $db = new PDOData();
        $ret = $db->doQuery("SELECT * FROM topic where staff_id = $staff_id;");
        $db->__destruct();
        return $ret;
    }

    //Lấy id của chủ đề cuối cùng
    public function getLastID() {
        $db = new PDOData();
        $ret = $db->doQuery("SELECT topic_id FROM topic ORDER BY topic_id DESC LIMIT 1");
        $db->__destruct();
        return $ret;
    }

    //Tạo chủ đề mới
    public function createTopic($topic_name, $description, $staff_id) {
        $db = new PDOData();
        $ret = $db->doSql("INSERT INTO topic (topic_name, description, staff_id) VALUES ('$topic_name','$description', '$staff_id')");
        $db->__destruct();
        return $ret;
    }

    //Cập nhật các chủ đề
    public function updateTopic($topic_id, $topic_name, $description) {
        $db = new PDOData();
        $ret = $db->doSql("UPDATE topic SET topic_name='$topic_name', description = '$description' 
WHERE topic_id= '$topic_id';");
        $db->__destruct();
        return $ret;
    }

    //Xóa chủ đề dựa trên topic_id
    public function deleteTopic($topic_id) {
        $db = new PDOData();
        $ret = $db->doSql("delete from topic where topic_id = '$topic_id'");
        $db->__destruct();
        return $ret;
    }
}