<?php

class intrestModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  intrest ");
        return $this->db->all();
    }

    public function getHikePlan($id)
    {
        $this->db->query("SELECT h.name ,h.image image FROM intrest_plans hp,intrest h, plan p WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id'");
        return $this->db->all();
    }
    public function getAllNames()
    {
        $this->db->query("SELECT name , id FROM  intrest ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM intrest WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    public function getHikeWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM intrest_plans hp,intrest h, plan p, user u WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->all();
    }
    public function addImage($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM intrest_plans hp,intrest h, plan p, user u WHERE h.id=hp.hike_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->single();
    }
    public function getAllHikeWithCreator()
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM intrest h, user u WHERE h.creator_id = u.id");
        return $this->db->all();
    }
    public function getHikeInfoWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM intrest h, user u WHERE h.creator_id = u.id and h.id = '$id'");
        return $this->db->single();
    }
    public function getPlanFromHike($id)
    {
        $this->db->query("SELECT p.id plan_id, p.price price, p.name name, h.name hike_name FROM intrest h, plan p ,intrest_plans hp WHERE h.id = hp.hike_id and p.id = hp.plan_id and h.id = '$id'");
        return $this->db->all();
    }
    
    public function hikeInfoByRef($hike)
    {
        $this->db->query("SELECT * FROM intrest WHERE refenrence_id = :hike");
        $this->db->bind(':hike', $hike);
        return $this->db->single();
    }

    
    
    public function getintrestByDate($date)
    {
        $this->db->query("SELECT * FROM intrest WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($data, $id , $nameImage)
    {
        try {
            $this->db->query("INSERT INTO
                intrest
            SET
                name = :name,
                description = :description,
                image = :image,
                water = :water,
                camping = :camping,
                signal_pref = :signal_pref,
                geocode = :geocode,
                creator_id = :creator_id
            ");

            $this->db->bind(':name', $data->name);
            $this->db->bind(':description', $data->description);
            $this->db->bind(':image', ($nameImage));
            $this->db->bind(':water', $data->water);
            $this->db->bind(':camping', $data->camping);
            $this->db->bind(':geocode', $data->geocode);
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
        $this->db->query('DELETE FROM intrest WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }

    public function edit($data, $id , $nameImage)
    {
        try {
            $this->db->query("UPDATE 
                intrest
            SET
                name = :name,
                description = :description,
                image = :image,
                water = :water,
                camping = :camping,
                signal_pref = :signal_pref,
                geocode = :geocode,
                creator_id = :creator_id
                WHERE id = $id
            ");

            $this->db->bind(':name', $data->name);
            $this->db->bind(':description', $data->description);
            $this->db->bind(':image', ($nameImage));
            $this->db->bind(':water', $data->water);
            $this->db->bind(':camping', $data->camping);
            $this->db->bind(':geocode', $data->geocode);
            $this->db->bind(':signal_pref', $data->signal_pref);
            $this->db->bind(':creator_id', $data->creator_id);
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }



}
