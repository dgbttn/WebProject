<?php

    namespace research\control;

    require_once("research/model/Research.php");
    require_once("optimize/utf8/ConvertUTF8.php");

    class ResearchControl {
        public function __construct() {
        }

        //Lấy tất cả các thông tin của nghiên cứu
        public function getAll() {
            $std = new \research\model\Research();
            $opt = new \optimize\utf8\ConvertUTF8();
            $data = $std->getAll();
            $res = ["status" => "1", "data" => $opt->utf8ize($data)];
            echo json_encode($res);
        }

        //Tạo nghiên cứu mới
        public function create() {
            $data=json_decode(file_get_contents('php://input'),1);
            $parent_id = $data['parent_id'];
            $name = $data['name'];
            $std = new \research\model\Research();
            $status = $std->addResearch($name, $parent_id);
            $id = $std->getLastID();
            $res = ["status" => $status, "data" => $id];
            echo json_encode($res);
        }

        //Xóa nghiên cứu
        public function delete() {
            $data=json_decode(file_get_contents('php://input'),1);
            $id = $data['id'];
            $std = new \research\model\Research();
            $status = $std->deleteByID($id);
            $res = ["status" => $status];
            echo json_encode($res);
        }

        //Cập nhật nghiên cứu
        public function update() {
            $data=json_decode(file_get_contents('php://input'),1);
            $id = $data['id'];
            $name = $data['name'];
            $std = new \research\model\Research();
            $status = $std->updateResearch($id, $name);
            $res = ["status" => $status];
            echo json_encode($res);
        }
    }