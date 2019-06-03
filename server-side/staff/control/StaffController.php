<?php
namespace staff\control;
require_once("staff/model/StaffModel.php");
require_once ("staff/model/Research_Staff.php");
require_once ("account/model/AccountModel.php");
require_once("optimize/utf8/ConvertUTF8.php");

class StaffController{
    public function __construct(){
    }

    // add account from excel
    public function addExcel() {
        $data=json_decode(file_get_contents('php://input'),1);
        $username = $data["username"];
        $password = $data["password"];
        $role = $data["role"];
        $full_name = $data["full_name"];
        $vnu_email = $data["vnu_email"];
        $faculty_id = $data["faculty_id"];
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        if(isset($username) && isset($password) && isset($role)){
            $checkAccount = new \account\model\AccountModel();
            if($checkAccount->checkExistAccount($username)){
                $status = $checkAccount->registerAccount($username,$hash_password,$role);
                $id = $checkAccount->getIdByName($username);
                $account_id = $id[0]['id'];
                $info = new \staff\model\StaffModel();
                $status = $info->addStaff('', $full_name, $vnu_email, '', '', $faculty_id, $account_id);
                $staff_id = $info->getStaffIdByAccount($account_id);
                $opt = new \optimize\utf8\ConvertUTF8();
                $ans = (object)null;
                $ans->staff_id = $staff_id[0]['staff_id'];
                $ans->full_name = $full_name;
                $ans->vnu_email = $vnu_email;
                $ans->id = $account_id;
                $ans->username = $username;

                $res = ["status" => $status, "data" => $opt->utf8ize($ans)];
                echo json_encode($res);
            } else {
                $res = ["status" => 0];
                echo json_encode($res);
            }
        } else {
            echo "Chua nhap du thong tin";
        }

    }

    // get Avatar
    public function getAvatar() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        if(isset($staff_id)) {
            $target_Path = "public/avatar/".$staff_id."/";
            if(!is_dir($target_Path)) {
                $target_Path = $target_Path = "public/avatar/temp/avatar.jpg";
            }
            else {
                $target_Path = "public/avatar/".$staff_id."/avatar.jpg";
            }
            $myfile = fopen($target_Path, "r");
            $data = ['data' => base64_encode(fread($myfile,filesize($target_Path)))];
            echo json_encode($data);
        }
    }

    // update Avatar
    public function updateAvatar() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $avatar = $data["avatar"];

        if(isset($staff_id) && isset($avatar)) {
            $target_Path = "public/avatar/".$staff_id."/";
            if(!is_dir($target_Path)) {
                mkdir($target_Path);
            }

            $splited = explode(',', substr( $avatar , 5 ) , 2);
            $mime=$splited[0];
            $data=$splited[1];
            $mime_split_without_base64=explode(';', $mime,2);
            $mime_split=explode('/', $mime_split_without_base64[0],2);
            $extension = 'jpg';
            $filename_avatar = "avatar.".$extension;
            file_put_contents($target_Path.$filename_avatar, base64_decode($data));
            $res = ['status' => 1];
            echo json_encode($res);
        }
    }

    //Thêm lĩnh vực quan tâm của 1 staff
    public function addInterestField() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $research_id = $data["research_id"];
        $staff = new \staff\model\Research_Staff();
        $status = $staff->createRelation($research_id, $staff_id);
        $res = ["status" => $status];
        echo json_encode($res);
    }

    //Xóa lĩnh vực quan tâm của 1 staff
    public function deleteInterestField() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $staff = new \staff\model\Research_Staff();
        $status = $staff->deleteRelation($staff_id);
        $res = ["status" => $status];
        echo json_encode($res);
    }

    //Lấy thông tin nghiên cứu quan tâm của staff
    public function getInterestField() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $staff = new \staff\model\Research_Staff();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data = $staff->getResearchID($staff_id);
        $res = ["status" => "1", "data"=> $opt->utf8ize($data)];
        echo json_encode($res);
    }

    //Lấy danh sách staff
    public function getAll() {
        $staff = new \staff\model\StaffModel();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data = $staff->getAllStaff();
        $res = ["status" => "1", "data"=> $opt->utf8ize($data)];
        echo json_encode($res);
    }

    //Lấy tất cả các nghiên cứu quan tâm của tất cả các staff
    public function getAllResearchStaff() {
        $staff = new \staff\model\Research_Staff();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data = $staff->getAll();
        $res = ["status" => "1", "data"=> $opt->utf8ize($data)];
        echo json_encode($res);
    }

    //Lấy thông tin của staff từ staff_id
    public function getStaffByID() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $staff = new \staff\model\StaffModel();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data = $staff->getStaffFacultyById($staff_id);
        $res = ["status" => "1", "data"=> $opt->utf8ize($data)];
        echo json_encode($res);
    }

    //Quản trị viên lấy thông tin staff
    public function adminListStaff(){
        $staff = new \staff\model\StaffModel();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data = $staff->getStaffByAdmin();
        $res = ["status" => "1", "data"=> $opt->utf8ize($data)];
        echo json_encode($res);
    }
    //Quản trị viên cập nhật tài khoản giảng viên
    public function adminEditStaff(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $full_name = $data["full_name"];
        $vnu_email = $data["vnu_email"];
        $staff_type = $data["staff_type"];
        $academic_title = $data["academic_title"];
        $faculty_id = $data["faculty_id"];
        if(isset($staff_id) && isset($full_name) && isset($vnu_email) && isset($staff_type) && isset($academic_title)){
            $info = new \staff\model\StaffModel();
            $status = $info->adminEditStaff($staff_id, $full_name, $vnu_email, $staff_type, $academic_title, $faculty_id);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }
    //Admin tạo tài khoản cho staff
    public function adminCreateStaff(){
        $data=json_decode(file_get_contents('php://input'),1);
        $full_name = $data["full_name"];
        $vnu_email = $data["vnu_email"];
        $staff_number = $data["staff_number"];
        $staff_type = $data["staff_type"];
        $academic_title = $data["academic_title"];
        $faculty_id = $data["faculty_id"];
        $account_id = $data["account_id"];
        if(isset($full_name) && isset($vnu_email)){
            $info = new \staff\model\StaffModel();
            $status = $info->addStaff($staff_number, $full_name, $vnu_email, $staff_type, $academic_title, $faculty_id, $account_id);
            $id = $info->getLastID();
            $res = ["status" => $status, "data" => $id];
            echo json_encode($res);
        } else {
            echo "";
        }
    }

    //Giảng viên cập nhật thông tin
    public function staffEditInfo(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $phone_number = $data["phone_number"];
        $other_mail = $data["other_mail"];
        $website = $data["website"];
        $address = $data["address"];
        if(isset($staff_id) && isset($phone_number) && isset($other_mail) && isset($website) && isset($address)){
            $info = new \staff\model\StaffModel();
            $status = $info->staffEditInfo($staff_id, $phone_number, $other_mail, $website, $address);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }

    //Giang viên cập nhật số điện thoại
    public function staffEditPhone(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $phone_number = $data["phone_number"];
        if(isset($staff_id) && isset($phone_number)){
            $info = new \staff\model\StaffModel();
            $status = $info->staffEditPhone($staff_id, $phone_number);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }

    //Giảng viên cập nhật email
    public function staffEditEmail(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $other_mail = $data["other_mail"];
        if(isset($staff_id) && isset($other_mail)){
            $info = new \staff\model\StaffModel();
            $status = $info->staffEditEmail($staff_id, $other_mail);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }

    //Giảng viên cập nhật website
    public function staffEditWebsite(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $website = $data["website"];
        if(isset($staff_id) && isset($website)){
            $info = new \staff\model\StaffModel();
            $status = $info->staffEditWebsite($staff_id, $website);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }

    //Giảng viên cập nhật địa chỉ
    public function staffEditAddress(){
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data["staff_id"];
        $address = $data["address"];
        if(isset($staff_id) && isset($address)){
            $info = new \staff\model\StaffModel();
            $status = $info->staffEditAddress($staff_id, $address);
            $res = ["status" => $status];
            echo json_encode($res);
        } else {
            echo "";
        }
    }
}