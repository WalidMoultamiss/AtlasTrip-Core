<?php

require_once '../vendor/autoload.php';

class intrest_user extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->intrest_user = $this->model('intrest_userModel');
    }

    public function plans()
    {
        $plans = $this->intrest_user->getAll();
        print_r(json_encode($plans));
    }
    

    public function info($id)
    {
        $RDV = $this->intrest_user->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById()
    {
        $RDV = $this->intrest_user->getId($this->data->id);
        print_r(json_encode($RDV));
    }


    public function infoByRef($rdv)
    {
        $RDV = $this->intrest_user->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }

    public function add()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                    $this->intrest_user->delete($id);
                    for ($i=0; $i < count($this->data); $i++) {
                        $plan = $this->intrest_user->add($this->data[$i]->id, $id);
                    }
                    if ($plan) {
                        print_r(json_encode(array(
                            "message" => "relationship Created with success",
                            "mine" => $this->intrest_user->getMine($id)
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
    public function getMine()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                    $relation = $this->intrest_user->getMine($id);
                    if ($relation) {
                        print_r(json_encode(
                            $relation
                        ));
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
    public function getNotMine()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                    $relation = $this->intrest_user->getNotMine($id);
                    if ($relation) {
                        print_r(json_encode(
                            $relation
                        ));
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
        $this->intrest_user->delete($this->data);
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
                    $RDV = $this->intrest_user->delete($this->data);
                    $reference = $infos->reference;
                    $RDV = $this->intrest_user->add($this->data, $reference);
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
