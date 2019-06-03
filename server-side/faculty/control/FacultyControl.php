<?php
    namespace faculty\control;
    use core\data\model\PDOData;

    require_once("faculty/model/Faculty.php");
    require_once("optimize/utf8/ConvertUTF8.php");
    class FacultyControl {
        public function __construct() {
        }

        //Lấy id của faculty theo tên
        public function getFacultyId() {
            $std = new \faculty\model\Faculty();
            $opt = new \optimize\utf8\ConvertUTF8();
            $data=json_decode(file_get_contents('php://input'),1);
            $faculty_name = $data['faculty_name'];
            $data = $std->getIDbyName($faculty_name);
            $res = ["status" => "1", "data" => $opt->utf8ize($data)];
            echo json_encode($res);
        }

        //Lấy thông tin của toàn bộ bảng Faculty
        public function getAll() {
            $std = new \faculty\model\Faculty();
            $opt = new \optimize\utf8\ConvertUTF8();
            $data = $std->getAll();
            $res = ["status" => "1", "data" => $opt->utf8ize($data)];
            echo json_encode($res);
        }

        //Lấy tất cả các bộ môn được quản lý bởi UET
        public function getAllUetOwner() {
            $std = new \faculty\model\Faculty();
            $opt = new \optimize\utf8\ConvertUTF8();
            $data = $std->getAllUetOwner();
            $res = ["status" => "1", "data" => $opt->utf8ize($data)];
            echo json_encode($res);
        }

        //Tạo thêm Faculty mới
        public function create() {
            $data=json_decode(file_get_contents('php://input'),1);
            $name = $data['name'];
            $type = $data['type'];
            $address = $data['address'];
            $phone = $data['phone'];
            $website = $data['website'];
            $std = new \faculty\model\Faculty();
            $status = $std->addFaculty($name, $type, $address, $phone, $website);
            $id = $std->getLastID();
            $res = ["status" => $status, "data" => $id];
            echo json_encode($res);
        }

        //Xóa Faculty
        public function delete() {
            $data=json_decode(file_get_contents('php://input'),1);
            $id = $data['id'];
            $std = new \faculty\model\Faculty();
            $status = $std->deleteByID($id);
            $res = ["status" => $status];
            echo json_encode($res);
        }

        //Cập nhật thông tin Faculty
        public function update() {
            $data=json_decode(file_get_contents('php://input'),1);
            $name = $data['name'];
            $type = $data['type'];
            $address = $data['address'];
            $phone = $data['phone'];
            $website = $data['website'];
            $id = $data['id'];
            $std = new \faculty\model\Faculty();
            $status = $std->updateFaculty($id, $name, $type, $address, $phone, $website);
            $res = ["status" => $status];
            echo json_encode($res);
        }
    }