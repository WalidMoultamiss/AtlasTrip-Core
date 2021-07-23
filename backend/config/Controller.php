<?php
use \Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

class Controller
{

    //Load the model :
    public $key = "test";

    public function model($model)
    {
        require_once '../backend/models/' . $model . '.php';
        return new $model();

    }

    public function view($url, $data = [])
    {
        if (file_exists('../backend/view/' . $url . '.php')) {
            require_once '../backend/view/' . $url . '.php';
        } else {
            die('View does not exist');
        }
    }

    public function auth($id, $role, $hash)
    {
        $iat = time();
        $exp = $iat + 60 * 6000;
        $payload = array(
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => $iat,
            'exp' => $exp,
            'id' => $id,
            'role' => $role,
            'hash' => $hash,
        );

        $jwt = JWT::encode($payload, $this->key, 'HS512');

        return $jwt;
    }
    public function verifyAuth($token)
    {
        $decoded = JWT::decode($token, $this->key, array('HS512'));
        return $decoded;
    }

    public static function getJsonFormData($body)
    {
        $data = json_decode($body);
        return json_last_error() === JSON_ERROR_NONE ? $data : [];
    }

    public static function getFormData()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return null;
        }
        $request = self::getJsonFormData($_POST['request'] ?? '');
        return (object)[
            "image" => $_FILES,
            "request" => $request
        ];
    }

    public static function uplaodImages($imagefile)
    {
        if ($imagefile == []) {
            return null;
        }
        $fileName = $imagefile['name'];
        $tempPath = $imagefile['tmp_name'];
        $fileSize = $imagefile['size'];
        $path = dirname(__DIR__) . "/public/storage/images/";
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg');
        $name = md5(time() . mt_rand(1, 1000000));
        if (in_array($fileExt, $valid_extensions)) {
            if ($fileSize < 5000000) {
                if (move_uploaded_file($tempPath, $path . $name . '.' . $fileExt)) {
                } else {
                    return array("message" => "Sorry, cant move the file", "status" => false);
                }
            } else {
                return array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false);
            }
        } else {
            return array("message" => "Sorry, only JPG, JPEG files are allowed", "status" => false);
        }
        return $name;
    }

}
