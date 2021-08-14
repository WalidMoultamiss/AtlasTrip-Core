<?php

class UserModel
{
  

    public function __construct()
    {
        $this->db = new DB();
    }

    public function getUsers()
    {
        $this->db->query("SELECT id,first_name,last_name,image,quote FROM user");
        return $this->db->all();
    }

    public function getUsersCardProfile($id)
    {
        $this->db->query("SELECT user.id as userID,user.first_name,user.created_at,user.last_name,user.image , (select count(*) from followers where followers.following_id = userID and followers.user_id = '$id') as following FROM user where  user.id != '$id' ORDER BY following ASC");
        return $this->db->all();
    }

    public function getUserByRef($reference)
    {
        $this->db->query("SELECT * FROM user WHERE reference = :reference");
        $this->db->bind(':reference', $reference);
        return $this->db->single();
    }
    // public function getMyInfo($id)
    // {
    //     $this->db->query("SELECT * FROM user WHERE id = :id");
    //     $this->db->bind(':id', $id);
    //     return $this->db->single();
    // }
    public function getMyInfo($id)
    {
        $this->db->query("SELECT user.id,user.first_name,user.last_name,user.date_birth,user.email,user.image,user.phone,user.quote,user.t_num_1,user.t_num_2,user.t_num_3,user.role,user.id as userID,
                         (SELECT count(*) FROM followers where followers.following_id = :id) as followers, 
                         (SELECT count(*) from like_post,user,posts WHERE like_post.user_id = user.id and like_post.post_id = posts.id and posts.user_id = :id and user.id != :id) as sumlikes,
                         (select COUNT(*) from posts where posts.user_id = :id) as num_of_my_posts,
                         (select count(*) from like_post,posts where like_post.post_id = posts.id and like_post.user_id = :id and posts.user_id = :id) as 'my_likes_of_my_posts'
                                    FROM user,posts where user.id = posts.user_id and posts.user_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM user WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }
    public function checkFollowUser($user,$following)
    {
        $this->db->query("SELECT count(*) as num_of_followers from followers where user_id = :user AND following_id = :following");
        $this->db->bind(':user', $user);
        $this->db->bind(':following', $following);
        return $this->db->single()->num_of_followers;
    }
    public function followUser($user,$following)
    {
        $this->db->query("INSERT INTO followers SET user_id = :user, following_id = :following");
        $this->db->bind(':user', $user);
        $this->db->bind(':following', $following);
        return $this->db->single();
    }
    public function unFollowUser($user,$following)
    {
        $this->db->query("DELETE FROM  followers WHERE user_id = :user AND following_id = :following");
        $this->db->bind(':user', $user);
        $this->db->bind(':following', $following);
        return $this->db->single();
    }

    
    public function getUserByRole($role)
    {
        echo $role;
        $this->db->query("SELECT * FROM user WHERE role = :role");
        $this->db->bind(':role', $role);
        return $this->db->all();
    }


    public function uniqidReal($lenght = 8) {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public function register($data)
    {
        $uniqueRef=strtoupper($this->uniqidReal());
        
        try {
            $this->db->query("INSERT INTO
                user
            SET
                first_name = :first_name,
                last_name = :last_name,
                phone = :phone,
                email = :email,
                password = :password,
                t_num_1 = :t_num_1,
                created_at = :created_at
            ");
            $this->db->bind(':first_name', $data->first_name);
            $this->db->bind(':last_name', $data->last_name);
            $this->db->bind(':phone', $data->phone);
            $this->db->bind(':email', $data->email);
            $this->db->bind(':password', $data->password);
            $this->db->bind(':t_num_1', $data->t_num_1);
            $this->db->bind(':created_at', time());
            $this->db->single();
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return $this->getUserByEmail($data->email);
    }
    public function edit($data, $id , $nameImage)
    {
        
        
        try {
            $this->db->query("UPDATE
                user
            SET
                first_name = :first_name,
                last_name = :last_name,
                phone = :phone,
                image = :image,
                quote = :quote,
                t_num_1 = :t_num_1,
                t_num_2 = :t_num_2,
                t_num_3 = :t_num_3

                WHERE id = :id
            ");
            $this->db->bind(':first_name', $data->first_name);
            $this->db->bind(':last_name', $data->last_name);
            $this->db->bind(':phone', $data->phone);
            $this->db->bind(':quote', $data->quote);
            $this->db->bind(':image', $nameImage);
            $this->db->bind(':t_num_1', $data->t_num_1);
            $this->db->bind(':t_num_2', $data->t_num_2);
            $this->db->bind(':t_num_3', $data->t_num_3);
            $this->db->bind(':id', $id);
            $this->db->single();
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return $this->getMyInfo($id);
    }


    
   
}
