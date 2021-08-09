<?php

require_once '../vendor/autoload.php';

class day extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->dayModel = $this->model('dayModel');
    }

    public function days()
    {
        $days = $this->dayModel->getAll();
        print_r(json_encode($days));
    }

    public function info($id)
    {
        $RDV = $this->dayModel->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById($id)
    {
        $day = $this->dayModel->getId($id);
        print_r(json_encode($day));
    }


    public function infoByRef($rdv)
    {
        $RDV = $this->dayModel->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }



    public function add()
    {
        die(print_r($this->data));
        $headers = apache_request_headers();
        $headers = isset($headers['Authorization']) ? explode(' ', $headers['Authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                    for ($i=0; $i < count($this->data); $i++) {
                        $day = $this->dayModel->add($this->data[$i], $id);
                    }
                    if ($day) {
                        print_r(json_encode(array(
                            "message" => "day Created with success",
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
        $this->dayModel->delete($this->data);
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
                    $RDV = $this->dayModel->delete($this->data);
                    $reference = $infos->reference;
                    $RDV = $this->dayModel->add($this->data, $reference);
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
