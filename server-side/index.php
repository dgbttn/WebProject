<?php
namespace core\control;
require_once("core/control/Routing.php");

//Bộ điều khiển mặt trước
class FrontController {
    public static function proc() {
        //Định tuyến
        $ret = Routing::proc();
        //Bao hàm tệp cài đặt lớp điều khiển
        $filename = $ret["moduleName"]."/control/".ucfirst($ret["controllerName"]).".php";
        require_once($filename);
        //Khai báo đối tượng lớp điều khiển
        $controllerName = "\\".$ret["moduleName"]."\\control\\".ucfirst($ret["controllerName"]);
        $controller = new $controllerName();
        //Kiểm tra phương thức có tồn tại hay không và thực thi
        if (method_exists($controller, $ret['actionName'])) {
            $action = $ret['actionName'];
            $controller->$action($ret['parameters']);
        } else {
            echo 'Không tìm thấy trang web';
        }
    }
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token, Access-Control-Allow-Origin, X-ACCESS_TOKEN');
header('Access-Control-Allow-Method: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    echo 'ok';
}
else {
    FrontController::proc();
}
