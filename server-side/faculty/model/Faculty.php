<?php
    namespace faculty\model;
    use core\data\PDOData;
    require_once("core/data/PDOData.php");

    class Faculty{
        //Khởi tạo DB
        public function __construct() {
        }

        //Lấy tât cả thông tin của faculty
        public function getAll() {
            $db = new PDOData();
            $ret = $db->doQuery("select * from faculty;");
            $db->__destruct();
            return $ret;
        }

        //Lấy tất cả thông tin của faculty của UET
        public function getAllUetOwner() {
            $db = new PDOData();
            $ret = $db->doQuery("select * from faculty where owner = 1;");
            $db->__destruct();
            return $ret;
        }

        //Lấy faculty_id cuối cùng
        public function getLastID() {
            $db = new PDOData();
            $ret = $db ->doQuery("SELECT faculty_id FROM faculty ORDER BY faculty_id DESC LIMIT 1");
            $db->__destruct();
            return $ret;
        }

        //Lấy id của faculty dựa trên tên
        public function getIDbyName($name) {
            $db = new PDOData();
            $ret = $db->doQuery("select faculty_id from faculty where name = '$name'");
            $db->__destruct();
            return $ret;
        }

        //Cập nhật thông tin cho faculty
        public function updateFaculty($id, $name, $type, $address, $phone, $web) {
            $db = new PDOData();
            $ret = $db->doSql("update faculty set name = '$name', type = '$type', address = '$address'
, phone_number = '$phone', website = '$web' where faculty_id = '$id'");
            $db->__destruct();
            return $ret;
        }

        //Xóa facuty bằng id
        public function deleteByID($id) {
            $db = new PDOData();
            $ret = $db->doSql("delete from faculty where faculty_id = '$id'");
            $db->__destruct();
            return $ret;
        }

        //Thêm Faculty vào DB
        public function addFaculty($name, $type, $address, $phone, $web) {
            $db = new PDOData();
            $ret = $db->doSql("insert into faculty(name, type, address, phone_number, website) 
values ('$name', '$type', '$address', '$phone', '$web') ");
            $db->__destruct();
            return $ret;
        }

        //Lấy thông tin theo Bộ môn
        public function searchByType($type) {
            $db = new PDOData();
            $ret = $db->doQuery("select * from faculty where type = 'Bo mon';");
            $db->__destruct();
            return $ret;
        }
    }