<?php
    namespace research\model;
    use core\data\PDOData;
    require_once("core/data/PDOData.php");

    class Research{
        //Khởi tạo DB
        public function __contruct(){
        }

        //Lấy danh sách các Research
        public function getAll(){
            $db = new PDOData();
            $ret = $db->doQuery("SELECT * FROM research;");
            $db->__destruct();
            return $ret;
        }

        //Lấy research_id cuối cùng
        public function getLastID() {
            $db = new PDOData();
            $ret = $db->doQuery("SELECT research_id FROM research ORDER BY research_id DESC LIMIT 1");
            $db->__destruct();
            return $ret;
        }

        //Lấy danh sách research_id từ research được chọn
        public function getByID($research_id){
            $db = new PDOData();
            $ret = $db->doQuery("SELECT * FROM research WHERE research_id = '$research_id';");
            $db->__destruct();
            return $ret;
        }

        //Lấy danh sách các research có chung parent_id
        public function getByParentID($parent_id){
            $db = new PDOData();
            $ret = $db->doQuery("SELECT * FROM research WHERE $parent_id = '$parent_id';");
            $db->__destruct();
            return $ret;
        }

        //Sửa thông tin Research
        public function updateResearch($research_id, $name){
            $db = new PDOData();
            $ret = $db->doSql("UPDATE research SET name='$name' WHERE research_id= '$research_id';");
            $db->__destruct();
            return $ret;
        }

        //Thêm thông tin Research
        public function addResearch($name, $parent_id){
            $db = new PDOData();
            $ret = $db->doSql("INSERT INTO research (name, parent_id) VALUES ('$name','$parent_id');");
            $db->__destruct();
            return $ret;
        }

        //Xóa nghiên cứu dựa trên id
        public function deleteByID($id) {
            $db = new PDOData();
            $ret = $db->doSql("delete from research where research_id = '$id'");
            $db->__destruct();
            return $ret;
        }
    }