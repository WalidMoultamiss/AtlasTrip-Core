<?php

class plan_hikeModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  plan ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM plan WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function planInfoByRef($plan)
    {
        $this->db->query("SELECT * FROM plans WHERE refenrence_id = :plan");
        $this->db->bind(':plan', $plan);
        return $this->db->single();
    }

    
    
    public function getplansByDate($date)
    {
        $this->db->query("SELECT * FROM plans WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($data, $id)
    {
        try {
            $this->db->query("INSERT INTO
                hikes_plans
            SET
                hike_id = :hike_id,
                plan_id = :plan_id
            ");

            $this->db->bind(':hike_id', $data->hike_id);
            $this->db->bind(':plan_id', $data->plan_id);
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM plans WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
