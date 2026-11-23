<?php

class User {
    private $db;
    private $id_user;
    private $username;

    public function __construct($db) {
        $this->db =$db;
    }

    public function getIdUser() { return $this->id_user; }
    public function getUsername() { return $this->username; }

    public function login($username,$password) {
        $stmt =$this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' =>$username]);
        $user =$stmt->fetch();

        if ($user && password_verify($password,$user['password'])) {
            $this->id_user =$user['id_user'];
            $this->username =$user['username'];
            return true;
        }
        return false;
    }
}