<?php

require_once '../vendor/autoload.php';

class plan extends Controller
{

    public $data = [];
    public $key = "test";

    public function __construct()
    {
        $this->planModel = $this->model('planModel');
        $this->hikeModel = $this->model('hikeModel');
        $this->dayModel = $this->model('dayModel');
        $this->plan_dayModel = $this->model('plan_dayModel');
        $this->day_hikeModel = $this->model('day_hikeModel');
    }

    public function plans()
    {
        $plans = $this->planModel->getAll();
        print_r(json_encode($plans));
    }
    public function plansExp()
    {
        $plans = $this->planModel->getAllExp();
        print_r(json_encode($plans));
    }
    public function countDay($id)
    {
        $plans = $this->planModel->countDay($id);
        print_r(json_encode($plans));
    }
    public function getDaysFromPlan($id)
    {
        $plans = $this->planModel->getDaysFromPlan($id);
        print_r(json_encode($plans));
    }
    public function getDaysFromPlanUniqueId($id)
    {
        $plans = $this->planModel->getDaysFromPlanUniqueId($id);
        print_r(json_encode($plans));
    }
    public function getDaysFromPlanWithHikes($id)
    {
        $days = $this->planModel->getDaysFromPlan($id);
        // die(print_r($days));
        for ($i=0; $i <count($days) ; $i++) {
            $days[$i] = $this->hikeModel->getHikesFromDay($days[$i]->id);
        }
        print_r(json_encode($days));
    }
    public function getAllExpWithCreator()
    {
        $plans = $this->planModel->getAllExpWithCreator();
        print_r(json_encode($plans));
    }

    public function info($id)
    {
        $RDV = $this->planModel->RDVInfo($id);
        print_r(json_encode($RDV));
    }

    public function infoById($id)
    {
        $plan = $this->planModel->getId($id);
        print_r(json_encode($plan));
    }


    public function infoByRef($rdv)
    {
        $RDV = $this->planModel->RDVInfoByRef($rdv);
        print_r(json_encode($RDV));
    }



    public function add()
    {
        // die(print_r($this->data));
        $headers = apache_request_headers();
        $headers = isset($headers['authorization']) ? explode(' ', $headers['authorization']) : null;
        if ($headers) {
            $process="";
            try {
                $infos = $this->verifyAuth($headers[1]);
                $process=$process."> verifying token";
                if ($infos->role === "user") {
                    $process=$process."> verifying role";
                    //get id of the creator
                    $id = $infos->id;
                    $process=$process."> get id user";
                    //create a plan and return it's id
                    $plan_name = $this->data->plan_name;
                    function clean($plan_name) {
                        $plan_name = str_replace(' ', '-', $plan_name); // Replaces all spaces with hyphens.
                        return preg_replace('/[^A-Za-z0-9\-]/', '', $plan_name); // Removes special chars.
                        }
                    $plan_name = clean($plan_name);
                    $micro = microtime();
                    $micro = explode('.',join('',explode(' ',$micro)))[1];
                    $uniqueId = $micro . $id . $plan_name;
                    $process=$process."> unique_id created ".$uniqueId;
                    $plan = $this->planModel->add($this->data->plan_name, $id,$uniqueId);
                    $process=$process."> plan created";
                    //add days for each day in data
                    for ($i=0; $i < count($this->data->data); $i++) {
                        //add day with it's number and return it's id
                        $day = $this->dayModel->add($i);
                        $process=$process."> add day";
                        // now i have the plan and the day lets create the relationship
                        $relationship = $this->plan_dayModel->add($plan->id,$day->id);
                        $process=$process."> relationship between day and plan has been created";
                        //let's create the relationship between days and hikes
                        for ($j=0; $j < count($this->data->data[$i]); $j++) {
                            $hike = $this->data->data[$i][$j];
                            for ($k=0; $k < count($this->data->data[$i][$j]); $k++) {
                                $relationship = $this->day_hikeModel->add($hike[$k],$day->id);
                                $process=$process."> relationship between day '$day->id' and hike '$hike[$j]' has been created";

                            }
                        }
                    }
                    
                    if ($plan) {
                        print_r(json_encode(array(
                            "error"=>false,
                            "message" => "plan Created with success",
                            "uniqueId"=>$uniqueId,
                            "data" => $process
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
        $this->planModel->delete($this->data);
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
                    $RDV = $this->planModel->delete($this->data);
                    $reference = $infos->reference;
                    $RDV = $this->planModel->add($this->data, $reference);
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
