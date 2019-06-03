<?php
namespace account\control;
use optimize\utf8\JWT;

require_once("account/model/AccountModel.php");
require_once ("staff/model/StaffModel.php");
require_once("optimize/utf8/ConvertUTF8.php");
require_once("optimize/jwt/JWT.php");

class AccountController{
    //validate
    public function validate(){
        $data=json_decode(file_get_contents('php://input'),1);
        $username = $data["username"];
        $password = $data["password"];

        if(isset($username) && isset($password)){
            $acc_model = new \account\model\AccountModel();
            $account = $acc_model->checkAccount($username);
            if(count($account) > 0){
                $hash = $account[0]['password'];
                if (password_verify($password, $hash)) {
                    $acc_id = $account[0]['id'];
                    $role = $account[0]['role'];
                    $staff_model = new \staff\model\StaffModel();
                    $staff_id = $staff_model->getStaffIdByAccount($acc_id);

                    $ans = (object)null;
                    $ans->staff_id = $staff_id[0]['staff_id'];
                    $ans->role = $role;
                    $ans->expired = 1;
                    $ans->username = $username;

                    $token = new \optimize\jwt\JWT();
                    $res = ["status" => 1, "data" => $token->encode_JWT($ans)];
                    echo json_encode($res);
                } else {
                    $res = ["status" => 0, "reason" => "Wrong password"];
                    echo json_encode($res);
                }

            } else {
                $res = ["status" => 0, "reason" => "Account does not exists"];
                echo json_encode($res);
            }
        }
    }

    //register
    public function register(){
        $data=json_decode(file_get_contents('php://input'),1);
        $username = $data["username"];
        $password = $data["password"];
        $role = $data["role"];
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        if(isset($username) && isset($password) && isset($role)){
            $checkAccount = new \account\model\AccountModel();
            if($checkAccount->checkExistAccount($username)){
                $status = $checkAccount->registerAccount($username,$hash_password,$role);
                $id = $checkAccount->getIdByName($username);
                $res = ["status" => $status, "data" => $id];
                echo json_encode($res);
            } else {
                $res = ["status" => 0];
                echo json_encode($res);
            }
        } else {
            echo "Chua nhap du thong tin";
        }
    }

    //Change Password
    public function changePassword(){
        $data=json_decode(file_get_contents('php://input'),1);
        $username = $data["username"];
        $old_password = $data["old_password"];
        $new_password = $data["new_password"];
        $hash_new = password_hash($new_password, PASSWORD_DEFAULT);
        if(isset($username) && isset($old_password) && isset($hash_new)){
            $acc_model = new \account\model\AccountModel();
            $account = $acc_model->checkAccount($username);
            if(count($account) > 0){
                $hash_old = $account[0]['password'];
                if (password_verify($old_password, $hash_old)) {
                    if (!password_verify($new_password, $hash_old)) {
                        $status = $acc_model->changePass($username, $hash_new);
                        $res = ["status" => 1];
                        echo json_encode($res);
                    }
                    else {
                        $res = ["status" => 0, "reason" => "Mat khau moi trung mat khau cu"];
                        echo json_encode($res);
                    }
                }
                else {
                    $res = ["status" => -1, "reason" => "Mat khau cu nhap chua dung"];
                    echo json_encode($res);
                }
            }
        }
    }

    //Delete user
    public function deleteUser(){
        $data=json_decode(file_get_contents('php://input'),1);
        $account_id = $data["account_id"];
        if(isset($account_id)){
            $delete = new \account\model\AccountModel();
            $status = $delete->deleteAccount($account_id);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "Xoa tai khoan that bai";
        }
    }


}