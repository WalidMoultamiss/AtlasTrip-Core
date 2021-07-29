<?php

require_once '../vendor/autoload.php';

class intrest extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->intrestModel = $this->model('intrestModel');
    }

    public function intrests()
    {
        $intrests = $this->intrestModel->getAll();
        for($i=0; $i<count($intrests);$i++){
            $intrests[$i]->check=true;
        };
        print_r(json_encode($intrests));
    }
    public function intrestsName()
    {
        $intrests = $this->intrestModel->getAllNames();
        print_r(json_encode($intrests));
    }
    public function intrestsPlan($id)
    {
        $intrests = $this->intrestModel->getintrestPlan($id);
        print_r(json_encode($intrests));
    }
    public function getintrestWithCreator($id)
    {
        $intrests = $this->intrestModel->getintrestWithCreator($id);
        print_r(json_encode($intrests));
    }
    public function getAllintrestWithCreator()
    {
        $intrests = $this->intrestModel->getAllintrestWithCreator();
        print_r(json_encode($intrests));
    }
    public function getintrestInfoWithCreator($id)
    {
        $intrests = $this->intrestModel->getintrestInfoWithCreator($id);
        print_r(json_encode($intrests));
    }
    public function getPlanFromintrest($id)
    {
        $intrests = $this->intrestModel->getPlanFromintrest($id);
        print_r(json_encode($intrests));
    }

    public function info($id)
    {
        $RDV = $this->intrestModel->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById($id)
    {
        $RDV = $this->intrestModel->getId($id);
        print_r(json_encode($RDV));
    }

    public function infoByRef($rdv)
    {
        $RDV = $this->intrestModel->RDVInfoByRef($rdv);
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
                        $intrest = $this->intrestModel->add(json_decode($request->json), $id ,$name);
                    
                    if ($intrest) {
                        print_r(json_encode(array(
                            "message" => "intrest Created with success",
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
        $this->intrestModel->delete($this->data);
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
                    $intrest = $this->intrestModel->edit(json_decode($request->json), $id ,$name);
                    if ($intrest) {
                        print_r(json_encode(array(
                            "message" => "intrest Edited with success",
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
