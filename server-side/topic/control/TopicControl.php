<?php

namespace topic\control;

require_once("topic/model/Topic.php");
require_once("optimize/utf8/ConvertUTF8.php");

class TopicControl{
    public function __construct(){
    }

    //Lấy thông tin chủ đề nghiên cứu của staff
    public function getTopicByStaffId() {
        $std = new \topic\model\Topic();
        $opt = new \optimize\utf8\ConvertUTF8();
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data['staff_id'];
        $data = $std->getTopicByStaffID($staff_id);
        $res = ["status" => "1", "data" => $opt->utf8ize($data)];
        echo json_encode($res);
    }

    //Tạo chủ đề nghiên cứu
    public function create() {
        $data=json_decode(file_get_contents('php://input'),1);
        $staff_id = $data['staff_id'];
        $topic_name = $data['topic_name'];
        $description = $data['description'];
        $std = new \topic\model\Topic();
        $status = $std->createTopic($topic_name, $description, $staff_id);
        $id = $std->getLastID();
        $res = ["status" => $status, "data" => $id];
        echo json_encode($res);
    }

    //Cập nhật chủ đề nghiên cứu
    public function update() {
        $std = new \topic\model\Topic();
        $data=json_decode(file_get_contents('php://input'),1);
        $topic_id = $data['topic_id'];
        $topic_name = $data['topic_name'];
        $description = $data['description'];
        $status = $std->updateTopic($topic_id, $topic_name, $description);
        $res = ["status" => $status];
        echo json_encode($res);
    }

    //Xóa chủ đề nghiên cứu
    public function delete() {
        $std = new \topic\model\Topic();
        $data=json_decode(file_get_contents('php://input'),1);
        $topic_id = $data['topic_id'];
        $status = $std->deleteTopic($topic_id);
        $res = ["status" => $status];
        echo json_encode($res);
    }
}