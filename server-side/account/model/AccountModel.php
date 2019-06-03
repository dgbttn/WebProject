<?php
namespace account\model;
use core\data\PDOData;
require_once("core/data/PDOData.php");

class AccountModel{
    //Khởi tạo DB
    public function __contruct(){
    }

    //Lấy id dựa vào username
    public function getIdByName($username) {
        $db = new PDOData();
        $ret = $db ->doQuery("SELECT id FROM account where username = '$username'");
        return $ret;
    }

    //Lấy data của username
    public function checkAccount($username) {
        $db = new PDOData();
        $data = $db->doPreparedQuery("SELECT * FROM account WHERE username LIKE ? ;", array($username));
        return $data;
    }

    //REGISTER-----------------------------
    //Hàm đăng ký user mới
    public function registerAccount($username, $password, $role = "lecture"){
        $db = new PDOData();
        $ret = $db->doSql("INSERT INTO `account`(`username`, `password`, `role`) VALUES ('$username','$password','$role');");
        return $ret;
    }

    //Hàm kiểm tra username đã tồn tại hay chưa
    public function checkExistAccount($username){
        $db = new PDOData();
        $checkAccount = $db->doQuery("SELECT * FROM account WHERE username = '$username';");
        if(count($checkAccount) > 0){
            return false;
        } else {
            return true;
        }
    }

    //CHANGE PASSWORD------------------------
    //Hàm kiểm tra trùng lặp giữa mk mới và mk
    public function checkNewPassword($old_password, $new_password){
        $db = new PDOData();
        $checkNewPass = $db->doQuery("SELECT password FROM account WHERE password = '$new_password';");
        if($old_password == $checkNewPass){
            return false;
        } else {
            return true;
        }
    }

    //Hàm kiểm tra mật khẩu cũ đã khớp hay không
    public function checkOldPassword($old_password){
        $db = new PDOData();
        $checkOldPass = $db->doQuery("SELECT password FROM account WHERE password = '$old_password';");
        if(count($old_password) > 0){
            return true;
        } else {
            return false;
        }
    }

    //Hàm thay đổi mật khẩu
    public function changePass($username ,$new_password){
        $db = new PDOData();
        $ret = $db->doSql("UPDATE account SET password ='$new_password' WHERE username = '$username';");
        return $ret;
    }

    //DELETE user
    public  function deleteAccount($account_id){
        $db = new PDOData();
        $ret = $db->doSql("DELETE FROM account WHERE id = '$account_id';");
        return $ret;
    }
}