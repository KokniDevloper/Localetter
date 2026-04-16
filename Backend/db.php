<?php
class database
{
    public $conn;
    public function __construct()
    {
        $this->conn = mysqli_connect('localhost', 'root', '', 'newsletterpro');
    }
    public function close()
    {
        mysqli_close($this->conn);
    }
}
