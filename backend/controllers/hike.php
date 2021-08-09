<?php

require_once '../vendor/autoload.php';

class hike extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->hikeModel = $this->model('hikeModel');
    }

    public function hikes()
    {
        $hikes = $this->hikeModel->getAll();
        print_r(json_encode($hikes));
    }
    public function getHikesFromDay($id)
    {
        $hikes = $this->hikeModel->getHikesFromDay($id);
        print_r(json_encode($hikes));
    }

    public function hikesImage()
    {
        $hikes = $this->hikeModel->getAllImages();
        print_r(json_encode($hikes));
    }
    public function hikesName()
    {
        $hikes = $this->hikeModel->getAllNames();
        print_r(json_encode($hikes));
    }
    public function hikesPlan($id)
    {
        $hikes = $this->hikeModel->getHikePlan($id);
        print_r(json_encode($hikes));
    }
    public function getHikeWithCreator($id)
    {
        $hikes = $this->hikeModel->getHikeWithCreator($id);
        print_r(json_encode($hikes));
    }
    public function getAllHikeWithCreator()
    {
        $hikes = $this->hikeModel->getAllHikeWithCreator();
        print_r(json_encode($hikes));
    }
    public function getHikeInfoWithCreator($id)
    {
        $hikes = $this->hikeModel->getHikeInfoWithCreator($id);
        print_r(json_encode($hikes));
    }
    public function getPlanFromHike($id)
    {
        $hikes = $this->hikeModel->getPlanFromHike($id);
        print_r(json_encode($hikes));
    }

    public function info($id)
    {
        $RDV = $this->hikeModel->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById($id)
    {
        $RDV = $this->hikeModel->getId($id);
        print_r(json_encode($RDV));
    }

    public function infoByRef($rdv)
    {
        $RDV = $this->hikeModel->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }

    public function add()
    {
        
        $request = (object) [
            "image" => $_FILES['image'],
            "json" => $_POST['json'],
        ];

        
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        // die(var_dump($headers));
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                        $name = $this->uplaodImages($request->image);
                        $hike = $this->hikeModel->add(json_decode($request->json), $id ,$name);
                    
                    if ($hike) {
                        print_r(json_encode(array(
                            "message" => "hike Created with success",
                            "data" => $this->data,
                        )));
                    }
                } else {
                    print_r(json_encode(array(
                        'error' => "You Don't Have permission to make this action",
                    )));
                    die();
                }
            } catch (\Throwable$th) {
                print_r(json_encode(array(
                    'error' => "Authentication error",
                )));
            }
        } else {
            print_r(json_encode(array(
                'error' => "Token is invalid", 'token' => $headers,
            )));
        }
    }

    public function delete()
    {
        $this->hikeModel->delete($this->data);
        print_r(json_encode(array(
            'message' => "the reservation canceled",
        )));
    }

    public function edit($id)
    {
        $request = (object) [
            "image" => $_FILES['image'],
            "json" => $_POST['json'],
        ];


        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role == "user") {
                    $name = $this->uplaodImages($request->image);
                    $hike = $this->hikeModel->edit(json_decode($request->json), $id ,$name);
                    if ($hike) {
                        print_r(json_encode(array(
                            "message" => "hike Edited with success",
                        )));
                    }
                } else {
                    print_r(json_encode(array(
                        'error' => "You Don't Have permission to make this action",
                    )));
                    die();
                }
            } catch (\Throwable$th) {
                print_r(json_encode(array(
                    'error' => "Authentication error",
                    
                )));
            }
        } else {
            print_r(json_encode(array(
                'error' => "token is invalid "
            )));
        }
    }

    public function search()
    {
        $result = $this->userModel->getBySearch($this->data);
        print_r(json_encode($result));
    }

}
