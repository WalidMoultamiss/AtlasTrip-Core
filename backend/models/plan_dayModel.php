<?php

class plan_dayModel
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


    public function add($plan_id,$day_id)
    {
        $result=true;
        try {
            $this->db->query("INSERT INTO
                plan_day
            SET
                day_id = :day_id,
                plan_id = :plan_id
            ");

            $this->db->bind(':day_id', $day_id);
            $this->db->bind(':plan_id', $plan_id);
            $this->db->single();
            $this->db->query("SELECT id FROM plan_day ORDER BY id DESC LIMIT 1");
            $result =  $this->db->single();
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return $result;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM plans WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
