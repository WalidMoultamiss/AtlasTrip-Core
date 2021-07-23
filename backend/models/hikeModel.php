<?php

class hikeModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  hikes ");
        return $this->db->all();
    }

    public function getHikePlan($id)
    {
        $this->db->query("SELECT h.name FROM hikes_plans hp,hikes h, plan p WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id'");
        return $this->db->all();
    }
    public function getAllNames()
    {
        $this->db->query("SELECT name , id FROM  hikes ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM hikes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    public function getHikeWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM hikes_plans hp,hikes h, plan p, user u WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->all();
    }
    public function addImage($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM hikes_plans hp,hikes h, plan p, user u WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->single();
    }
    public function getAllHikeWithCreator()
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM hikes h, user u WHERE h.creator_id = u.id");
        return $this->db->all();
    }
    public function getHikeInfoWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM hikes h, user u WHERE h.creator_id = u.id and h.id = '$id'");
        return $this->db->single();
    }
    public function getPlanFromHike($id)
    {
        $this->db->query("SELECT p.id plan_id, p.price price, p.name name, h.name hike_name FROM hikes h, plan p ,hikes_plans hp WHERE h.id = hp.hike_id and p.id = hp.plan_id and h.id = '$id'");
        return $this->db->all();
    }
    
    public function hikeInfoByRef($hike)
    {
        $this->db->query("SELECT * FROM hikes WHERE refenrence_id = :hike");
        $this->db->bind(':hike', $hike);
        return $this->db->single();
    }

    
    
    public function gethikesByDate($date)
    {
        $this->db->query("SELECT * FROM hikes WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($data, $id , $nameImage)
    {
        try {
            $this->db->query("INSERT INTO
                hikes
            SET
                name = :name,
                description = :description,
                image = :image,
                water = :water,
                camping = :camping,
                signal_pref = :signal_pref,
                creator_id = :creator_id
            ");

            $this->db->bind(':name', $data->name);
            $this->db->bind(':description', $data->description);
            $this->db->bind(':image', ($nameImage));
            $this->db->bind(':water', $data->water);
            $this->db->bind(':camping', $data->camping);
            $this->db->bind(':signal_pref', $data->signal_pref);
            $this->db->bind(':creator_id', $id);
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM hikes WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
