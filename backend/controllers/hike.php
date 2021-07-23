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

    public function infoById()
    {
        $RDV = $this->hikeModel->getId($this->data->id);
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

        // die(var_dump(json_decode($request->json)));

        $headers = apache_request_headers();
        $headers = isset($headers['Authorization']) ? explode(' ', $headers['Authorization']) : null;
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

    public function edit()
    {

        $headers = apache_request_headers();
        $headers = isset($headers['Authorization']) ? explode(' ', $headers['Authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role == "patient") {
                    $RDV = $this->hikeModel->delete($this->data);
                    $reference = $infos->reference;
                    $RDV = $this->hikeModel->add($this->data, $reference);
                    if ($RDV) {
                        print_r(json_encode(array(
                            "message" => "RDV Edited with success",
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
                'error' => "token is invalid",
            )));
        }
    }

    public function search()
    {
        $result = $this->userModel->getBySearch($this->data);
        print_r(json_encode($result));
    }

}
