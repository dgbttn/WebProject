<?php
namespace staff\model;
use core\data\PDOData;
require_once("core/data/PDOData.php");

class StaffModel{
    //Kết nối DB
    public function __contruct(){
    }

    //Lấy staff_id staff bằng account_id
    public function getStaffIdByAccount($account_id) {
        $db = new PDOData();
        $ret = $db->doQuery("select staff_id from staff where account_id = '$account_id';");
        $db->__destruct();
        return $ret;
    }

    //Lấy faculty từ staff_id
    public function getStaffFacultyById($staff_id) {
        $db = new PDOData();
        $ret = $db->doQuery("select staff.*, faculty.name from staff inner join faculty on staff.faculty_id = faculty.faculty_id where staff_id = $staff_id;");
        $db->__destruct();
        return $ret;
    }

    //Hiển thị tất cả các staff hiện có
    public function getAllStaff(){
        $db = new PDOData();
        $ret = $db->doQuery("SELECT * FROM staff;");
        $db->__destruct();
        return $ret;
    }

    //Hiển thị đầy đủ thông tin staff
    public function getStaffByAdmin(){
        $db = new PDOData();
        $ret = $db->doQuery("SELECT staff.faculty_id, account.username, faculty.name, staff.staff_id, staff.full_name, staff.vnu_email, staff.staff_type, staff.academic_title, staff.account_id, staff.staff_number 
FROM ((staff INNER JOIN account ON staff.account_id = account.id) INNER JOIN faculty 
ON staff.faculty_id = faculty.faculty_id);");
        $db->__destruct();
        return $ret;
    }

    //Lấy thông tin Staff từ danh sách
    public function getStaffById($staff_id) {
        $db = new PDOData();
        $ret = $db->doQuery("SELECT * FROM staff WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Lấy ID của bản ghi cuối cùng
    public function getLastID() {
        $db = new PDOData();
        $ret = $db ->doQuery("SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1");
        $db->__destruct();
        return $ret;
    }

    //Admin thêm tài khoản
    public function addStaff($staff_number, $full_name, $vnu_email, $staff_type = '', $academic_title = '', $faculty_id , $account_id){
        $db = new PDOData();
        $ret = $db->doSql("INSERT INTO staff (staff_number, full_name, vnu_email, staff_type, academic_title, faculty_id, account_id) 
VALUES ('$staff_number', '$full_name', '$vnu_email', '$staff_type', '$academic_title', '$faculty_id', '$account_id');");
        $db->__destruct();
        return $ret;
    }

    //Sửa thông tin tài khoản
    public function editInfoStaff($staff_id, $full_name, $vnu_email, $staff_number, $address, $phone_number, $website, $other_email, $emai, $academic_title, $faculty_id, $description, $staff_type, $account_id){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET staff_id = '$staff_id', full_name = '$full_name', vnu_email = '$vnu_email', staff_number = '$staff_number', address = '$address', phone_number = '$phone_number', website = '$website', other_email = '$other_email', emai = '$emai', academic_title = '$academic_title', faculty_id`= '$faculty_id', description = '$description', staff_type = '$staff_type', account_id = '$account_id' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Admin sua thong tin tai khoan
    public function adminEditStaff($staff_id, $full_name, $vnu_email, $staff_type, $academic_title, $faculty_id){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET full_name = '$full_name', vnu_email = '$vnu_email', staff_type = '$staff_type', academic_title = '$academic_title', faculty_id = '$faculty_id' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Giảng viên sửa thông tin
    public function staffEditInfo($staff_id, $phone_number, $other_mail, $website, $address){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET phone_number = '$phone_number', other_email = '$other_mail', website = '$website', address = '$address' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Sửa số điện thoại
    public function staffEditPhone($staff_id, $phone_number){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET phone_number = '$phone_number' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Sửa Email
    public function staffEditEmail($staff_id, $other_email){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET other_email = '$other_email' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Sửa Website
    public function staffEditWebsite($staff_id, $website){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET website = '$website' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }

    //Sửa địa chỉ
    public function staffEditAddress($staff_id, $address){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE staff SET address = '$address' WHERE staff_id = '$staff_id';");
        $db->__destruct();
        return $ret;
    }
}