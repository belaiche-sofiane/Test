<?php

abstract class Model
{
    protected $dbh;
    protected $stmt;

    public function __construct()
    {
        $this->dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        $this->stmt->execute();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 // Method wich verify if the user exists in the database
    public function RegisterVerification(){
        $email = $_POST['email'];
        $this-> stmt = $this->dbh->prepare("SELECT * FROM users WHERE email=?");
        $this->stmt->execute([$email]); 
        $user = $this->stmt->fetch();
        if ($user) {
            echo '<h1> This email already exist </h1>';
        }  
    }
}