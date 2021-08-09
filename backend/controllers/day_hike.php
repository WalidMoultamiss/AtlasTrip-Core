<?php

require_once '../vendor/autoload.php';

class day_hike extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->day_hike = $this->model('day_hikeModel');
    }

    public function plans()
    {
        $plans = $this->day_hike->getAll();
        print_r(json_encode($plans));
    }

    public function info($id)
    {
        $RDV = $this->day_hike->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById()
    {
        $RDV = $this->day_hike->getId($this->data->id);
        print_r(json_encode($RDV));
    }


    public function infoByRef($rdv)
    {
        $RDV = $this->day_hike->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }

    public function add()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['Authorization']) ? explode(' ', $headers['Authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                    for ($i=0; $i < count($this->data); $i++) {
                        $plan = $this->day_hike->add($this->data[$i], $id);
                    }
                    if ($plan) {
                        print_r(json_encode(array(
                            "message" => "relationship Created with success",
                            "data" => $this->data
                        )));
                    }
                } else {
                    print_r(json_encode(array(
                        'error' => "You Don't Have permission to make this action",
                    )));
                    die();
                }
            } catch (\Throwable $th) {
                print_r(json_encode(array(
                    'error' => "Authentication error",
                )));
            }
        } else {
            print_r(json_encode(array(
                'error' => "Token is invalid",'token'=> $headers
            )));
        }
    }

    public function delete()
    {
        $this->day_hike->delete($this->data);
        print_r(json_encode(array(
            'message' => "the reservation canceled"
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
                    $RDV = $this->day_hike->delete($this->data);
                    $reference = $infos->reference;
                    $RDV = $this->day_hike->add($this->data, $reference);
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
            } catch (\Throwable $th) {
                print_r(json_encode(array(
                    'error' => "Authentication error",
                )));
            }
        } else {
            print_r(json_encode(array(
                'error' => "token is invalid"
            )));
        }
    }


    public function search(){
        $result = $this->userModel->getBySearch($this->data);
        print_r(json_encode($result));
    }

}
