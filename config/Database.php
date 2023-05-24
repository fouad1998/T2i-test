<?php


namespace T2i\Config;

use PDO;

class Database
{
   // DB Params
   private $host = 'sql7.freemysqlhosting.net';
   private $db_name = 'sql7620512';
   private $username = 'sql7620512';
   private $password = 'rlUHAKWjC3';
   private $conn;

   // DB Connect
   public function connect()
   {
      if ($this->conn !== null) {
         return $this->conn;
      }

      $this->conn = null;
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $this->conn;
   }
}
