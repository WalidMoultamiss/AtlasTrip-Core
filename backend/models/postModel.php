<?php

class postModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll($id)
    {
        $this->db->query("SELECT posts.*, posts.id as postOfId,user.first_name,user.id as usersID,user.last_name,user.image as user_image,( select count(*) from like_post where like_post.post_id = postOfId) as likes,( select count(*) from like_post where like_post.post_id = postOfId and like_post.user_id = '$id') as likedByMe FROM user,posts where user.id = posts.user_id ORDER BY posts.created_at desc");
        return $this->db->all();
    }
    public function getpostsFromDay($id)
    {
        $this->db->query("SELECT posts.* from posts,day,day_post WHERE day_post.day_id = day.id and day_post.post_id = posts.id and day.id = '$id'");
        return $this->db->all();
    }
    public function getAllImages()
    {
        $this->db->query("SELECT posts.image, posts.id FROM  posts ");
        return $this->db->all();
    }
    public function getLike($user_id , $post_id)
    {
        $this->db->query("SELECT count(*) as likes from like_post where user_id = '$user_id' and post_id = '$post_id' ");
        return $this->db->all();
    }
    public function likesCounter($post_id)
    {
        $this->db->query("SELECT count(*) as likes from like_post where post_id = '$post_id' ");
        return $this->db->all();
    }
    public function likePost($user_id, $post_id)
    {
        try {
            $this->db->query("INSERT INTO
                like_post
            SET
                user_id = :user_id,
                created_at = :created_at,
                post_id = :post_id
            ");

            
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':post_id', $post_id);
            $this->db->bind(':created_at', time());
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    

    public function getpostPlan($id)
    {
        $this->db->query("SELECT h.name ,h.image image FROM posts_plans hp,posts h, plan p WHERE h.id=hp.post_id and p.id=hp.plan_id and p.id='$id'");
        return $this->db->all();
    }

    public function getAllNames()
    {
        $this->db->query("SELECT name , id FROM  posts ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    public function getpostWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM posts_plans hp,posts h, plan p, user u WHERE h.id=hp.post_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->all();
    }
    public function addImage($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM posts_plans hp,posts h, plan p, user u WHERE h.id=hp.post_id and p.id=hp.plan_id and p.id='$id' and h.creator_id = u.id");
        return $this->db->single();
    }
    public function getAllpostWithCreator()
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM posts h, user u WHERE h.creator_id = u.id");
        return $this->db->all();
    }
    public function getpostInfoWithCreator($id)
    {
        $this->db->query("SELECT u.first_name,u.last_name,h.* FROM posts h, user u WHERE h.creator_id = u.id and h.id = '$id'");
        return $this->db->single();
    }
    public function getPlanFrompost($id)
    {
        $this->db->query("SELECT p.id plan_id, p.price price, p.name name, h.name post_name FROM posts h, plan p ,posts_plans hp WHERE h.id = hp.post_id and p.id = hp.plan_id and h.id = '$id'");
        return $this->db->all();
    }
    
    public function postInfoByRef($post)
    {
        $this->db->query("SELECT * FROM posts WHERE refenrence_id = :post");
        $this->db->bind(':post', $post);
        return $this->db->single();
    }

    
    
    public function getpostsByDate($date)
    {
        $this->db->query("SELECT * FROM posts WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }


    public function add($text, $user_id , $unique_id , $nameImage)
    {
        try {
            $this->db->query("INSERT INTO
                posts
            SET
                text = :text,
                image = :image,
                user_id = :user_id,
                created_at = :created_at,
                unique_id = :unique_id

                
            ");

            $this->db->bind(':text', $text->text);
            $this->db->bind(':image', $nameImage);
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':unique_id', $unique_id);
            $this->db->bind(':created_at', time());
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM posts WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }

    public function edit($data, $id , $nameImage)
    {
        try {
            $this->db->query("UPDATE 
                posts
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
