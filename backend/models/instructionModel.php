<?php

class instructionModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  instruction");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM instruction WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function instructionInfoByRef($instruction)
    {
        $this->db->query("SELECT * FROM instructions WHERE refenrence_id = :instruction");
        $this->db->bind(':instruction', $instruction);
        return $this->db->single();
    }

    
    
    public function getinstructionsByDate($date)
    {
        $this->db->query("SELECT * FROM instructions WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($data)
    {
        try {
            $this->db->query("INSERT INTO
                instruction
            SET
                name = :name,
                image = :image,
                video_link = :video_link,
                go_to = :go_to,
                rating = :rating,
                points = :points,
                seconds = :seconds
            ");

            $this->db->bind(':name', $data->name);
            $this->db->bind(':image', $data->image);
            $this->db->bind(':video_link', $data->video_link);
            $this->db->bind(':points', $data->points);
            $this->db->bind(':rating', $data->rating);
            $this->db->bind(':go_to', $data->go_to);
            $this->db->bind(':seconds', $data->seconds);
            $this->db->single();
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM instructions WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
