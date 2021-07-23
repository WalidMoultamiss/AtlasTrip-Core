<?php

class productModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM  product ");
        return $this->db->all();
    }
    
    public function getId($id)
    {
        $this->db->query("SELECT * FROM product WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function productInfoByRef($product)
    {
        $this->db->query("SELECT * FROM products WHERE refenrence_id = :product");
        $this->db->bind(':product', $product);
        return $this->db->single();
    }

    
    
    public function getproductsByDate($date)
    {
        $this->db->query("SELECT * FROM products WHERE date = :date");
        $this->db->bind(':date', $date);
        return $this->db->single();
    }

    public function uniqidReal($lenght = 15) {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }


    public function add($data)
    {
        try {
            $this->db->query("INSERT INTO
                product
            SET
                name = :name,
                image = :image,
                website = :website,
                unique_id = :unique_id
            ");

            $this->db->bind(':name', $data->name);
            $this->db->bind(':image', $data->image);
            $this->db->bind(':website', $data->website);
            $this->db->bind(':unique_id', uniqid());
            $this->db->single();
            
        } catch (\PDOExeption $err) {
            return $err->getMessage();
            die();
        }
        return true;
    }

    public function delete($data)
    {
        $this->db->query('DELETE FROM products WHERE id=:id');
        $this->db->bind(':id', $data->id);
        $this->db->execute();
    }




}
