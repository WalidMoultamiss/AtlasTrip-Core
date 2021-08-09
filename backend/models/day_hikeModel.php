<?php

class day_hikeModel
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


    public function add($hike_id,$day_id)
    {
        try {
            $this->db->query("INSERT INTO
                day_hike
            SET
                day_id = :day_id,
                hike_id = :hike_id
            ");

            $this->db->bind(':day_id', $day_id);
            $this->db->bind(':hike_id', $hike_id);
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
