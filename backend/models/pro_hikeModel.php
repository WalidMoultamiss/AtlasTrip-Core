<?php

class pro_hikeModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  pro_hike ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM pro_hike WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function pro_hikeInfoByRef($pro_hike)
    {
        $this->db->query("SELECT * FROM pro_hike WHERE refenrence_id = :pro_hike");
        $this->db->bind(':pro_hike', $pro_hike);
        return $this->db->single();
    }

    
    
    public function getpro_hikeByDate($date)
    {
        $this->db->query("SELECT * FROM pro_hike WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($data)
    {
        try {
            $this->db->query("INSERT INTO
                pro_hike
            SET
                hike_id = :hike_id,
                need_percent = :need_percent,
                product_id = :product_id
            ");

            $this->db->bind(':need_percent', $data->need_percent);
            $this->db->bind(':hike_id', $data->hike_id);
            $this->db->bind(':product_id', $data->product_id);
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM pro_hike WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
