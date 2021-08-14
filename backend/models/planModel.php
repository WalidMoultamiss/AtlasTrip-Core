<?php

class planModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  plan where status = 'active'");
        return $this->db->all();
    }
    public function getinfoOfAllPlans()
    {
        $this->db->query("SELECT count(*)-1 as users,(SELECT count(*) FROM plan) as plans from user");
        return $this->db->single();
    }
    public function getAllExp()
    {
        $this->db->query("SELECT * FROM  plan where status = 'experiences' ORDER BY created_at DESC"  );
        return $this->db->all();
    }
    public function countDay($id)
    {
        $this->db->query("SELECT COUNT(*)as days FROM plan,plan_day where plan_day.plan_id = plan.id and plan_id = '$id'");
        return $this->db->all();
    }
    public function getDaysFromPlan($id)
    {
        $this->db->query(" SELECT day.* FROM plan,plan_day,day where plan_day.plan_id = plan.id and plan_day.day_id = day.id and plan_id = '$id'");
        return $this->db->all();
    }
    public function getDaysFromPlanUniqueId($id)
    {
        $this->db->query("SELECT day.*, user.first_name,user.last_name,user.id as user_id FROM plan,plan_day,day,user where plan_day.plan_id = plan.id and plan_day.day_id = day.id and unique_id ='$id' and plan.created_with = user.id");
        return $this->db->all();
    }
    public function getAllExpWithCreator()
    {
        $this->db->query("SELECT plan.*,user.first_name,user.last_name,user.image FROM plan,user where user.id = plan.created_with and status = 'experiences' ORDER BY plan.created_at DESC");
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


    public function add($data, $id,$unique_id)
    {
        $result = true;
        try {
            $this->db->query("INSERT INTO
                plan
            SET
                name = :name,
                price = :price,
                created_with = :created_with,
                created_at = :created_at,
                unique_id = :unique_id
            ");

            $this->db->bind(':name', $data);
            $this->db->bind(':price', '300');
            $this->db->bind(':created_with', $id);
            $this->db->bind(':created_at', time());
            $this->db->bind(':unique_id', $unique_id);
            $this->db->single();
            $this->db->query("SELECT id FROM plan ORDER BY id DESC LIMIT 1");
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
