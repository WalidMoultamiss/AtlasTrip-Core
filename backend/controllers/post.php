<?php

require_once '../vendor/autoload.php';

class post extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->postModel = $this->model('postModel');
    }

    // public function posts()
    // {
    //     $posts = $this->postModel->getAll();
    //     print_r(json_encode($posts));
    // }
    public function getpostsFromDay($id)
    {
        $posts = $this->postModel->getpostsFromDay($id);
        print_r(json_encode($posts));
    }

    public function postsImage()
    {
        $posts = $this->postModel->getAllImages();
        print_r(json_encode($posts));
    }
    public function postsName()
    {
        $posts = $this->postModel->getAllNames();
        print_r(json_encode($posts));
    }
    public function postsPlan($id)
    {
        $posts = $this->postModel->getpostPlan($id);
        print_r(json_encode($posts));
    }
    public function getpostWithCreator($id)
    {
        $posts = $this->postModel->getpostWithCreator($id);
        print_r(json_encode($posts));
    }
    public function getAllpostWithCreator()
    {
        $posts = $this->postModel->getAllpostWithCreator();
        print_r(json_encode($posts));
    }
    public function getpostInfoWithCreator($id)
    {
        $posts = $this->postModel->getpostInfoWithCreator($id);
        print_r(json_encode($posts));
    }
    public function getPlanFrompost($id)
    {
        $posts = $this->postModel->getPlanFrompost($id);
        print_r(json_encode($posts));
    }

    public function info($id)
    {
        $RDV = $this->postModel->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById($id)
    {
        $RDV = $this->postModel->getId($id);
        print_r(json_encode($RDV));
    }

    public function infoByRef($rdv)
    {
        $RDV = $this->postModel->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }


    public function addLike()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                        $likeTest = $this->postModel->getLike($id,$this->data->post_id);
                        if($likeTest[0]->likes == 0){
                            $like = $this->postModel->likePost($id,$this->data->post_id);
                        }else{
                            $number_of_likes = $this->postModel->likesCounter($this->data->post_id);
                            print_r(json_encode(array(
                                "message" => "you already liked this post",
                                "likes"=> $number_of_likes[0]->likes
                            )));
                            die();
                        }
                    if ($like) {
                        $number_of_likes = $this->postModel->likesCounter($this->data->post_id);
                        print_r(json_encode(array(
                            "message" => "you like the post",
                            "likes"=> $number_of_likes[0]->likes
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
    public function posts()
    {
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            try {
                $infos = $this->verifyAuth($headers[1]);
                if ($infos->role === "user") {
                    $id = $infos->id;
                        $posts = $this->postModel->getAll($id);
                    if ($posts) {
                        print_r(json_encode($posts));
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
                        $post = $this->postModel->add(json_decode($request->json), $id,$this->uniqidReal() ,$name);
                    if ($post) {
                        print_r(json_encode(array(
                            "message" => "post Created with success",
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
        $this->postModel->delete($this->data);
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
                    $post = $this->postModel->edit(json_decode($request->json), $id ,$name);
                    if ($post) {
                        print_r(json_encode(array(
                            "message" => "post Edited with success",
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
