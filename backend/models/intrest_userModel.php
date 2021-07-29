<?php

class intrest_userModel
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
    public function getMine($id)
    {
        $this->db->query("SELECT i.* from user u, intrest i, intrest_user iu WHERE u.id = iu.user_id and i.id = iu.intrest_id and u.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->all();
    }
    public function getNotMine($id)
    {
        $this->db->query("SELECT i.* from user u, intrest i, intrest_user iu WHERE u.id = iu.user_id and i.id = iu.intrest_id and u.id != :id");
        $this->db->bind(':id', $id);
        return $this->db->all();
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
                intrest_user
            SET
                intrest_id = :intrest_id,
                user_id = :user_id
            ");

            $this->db->bind(':intrest_id', $data);
            $this->db->bind(':user_id',  $id);
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM intrest_user WHERE user_id=:id');
        $this->db->bind(':id', $id);
        $this->db->execute();
    }




}
