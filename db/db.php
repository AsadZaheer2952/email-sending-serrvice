<?php


class db
{
    private $host="localhost";
    private $username="root";
    private $password="";
    private $database="email_sending_service";
    public $con;
    public function getConnection()
    {
        $this->con = null;
        try{
            $this->con = mysqli_connect($this->host , $this->username , $this->password , $this->database);
//            echo "connection Successsfully";
        }catch (Exception $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->con;
    }
}