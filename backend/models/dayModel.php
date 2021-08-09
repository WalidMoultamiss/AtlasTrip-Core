<?php

class dayModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  day ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM day WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function dayInfoByRef($day)
    {
        $this->db->query("SELECT * FROM days WHERE refenrence_id = :day");
        $this->db->bind(':day', $day);
        return $this->db->single();
    }

    
    
    public function getdaysByDate($date)
    {
        $this->db->query("SELECT * FROM days WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($i)
    {
        $result = true;
        try {
            $this->db->query("INSERT INTO
                day
            SET
                day_number = :num
            ");

            $this->db->bind(':num', $i);
            $this->db->single();
            $this->db->query("SELECT id FROM day ORDER BY id DESC LIMIT 1");
            $result =  $this->db->single();
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return $result;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM days WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
