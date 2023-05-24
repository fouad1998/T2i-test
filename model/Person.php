<?php

namespace T2i\Model;

use Exception;
use PDO;

class Person
{
   private $db;
   private $table = 'person';

   public $id = "";
   public $firstname = "";
   public $lastname = "";
   public $birthday = "";
   public $address = "";
   public $email = "";
   public $phone = "";

   function __construct($db)
   {
      $this->db = $db;
   }

   public function set($row)
   {
      $this->id = $row['id'];
      $this->firstname = $row['firstname'];
      $this->lastname = $row['lastname'];
      $this->email = $row['email'];
      $this->address = $row['address'];
      $this->birthday = $row['birthday'];
      $this->phone = $row['phone'];
   }

   public function getAll()
   {
      // Create query
      $query = 'SELECT  p.* FROM ' . $this->table . ' p
                     ORDER BY p.id DESC';

      // Prepare statement
      $stmt = $this->db->connect()->prepare($query);

      // Execute query
      $stmt->execute();

      // Get row count
      $num = $stmt->rowCount();

      // Check if any posts
      if ($num > 0) {
         $person_arr = array();
         foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            extract($row);

            $person_item = array(
               'id' => $id,
               'firstname' => $firstname,
               'lastname' => $lastname,
               'birthday' => $birthday,
               'address' => $address,
               'email' => $email,
               'phone' => $phone
            );

            array_push(
               $person_arr,
               $person_item
            );
         }

         return $person_arr;
      }

      return array();
   }

   public function get($id)
   {
      if (!is_numeric($id)) {
         throw new Exception("Invalid id type: " . $id);
      }

      // Create query
      $query = 'SELECT p.* FROM ' . $this->table . ' p WHERE p.id = ?';

      // Prepare statement
      $stmt = $this->db->connect()->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties
      $this->set($row);
   }

   public function create()
   {

      // Create query
      $query = 'INSERT INTO ' . $this->table . ' SET 
         firstname = :firstname, 
         lastname = :lastname, 
         email = :email,
         address = :address,
         birthday = :birthday,
         phone = :phone
      ';

      // Prepare statement
      $stmt = $this->db->connect()->prepare($query);

      // Clean data
      $this->cleanData();

      // Bind data
      $stmt->bindParam(':firstname', $this->firstname);
      $stmt->bindParam(':lastname', $this->lastname);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':address', $this->address);
      $stmt->bindParam(':birthday', $this->birthday);
      $stmt->bindParam(':phone', $this->phone);

      // Execute query
      if ($stmt->execute()) {
         return true;
      }


      throw new Exception("failed to execute insert statement");
   }


   public function update()
   {
      // Create query
      $query = 'UPDATE ' . $this->table . '
                              SET 
                                 firstname = :firstname, 
                                 lastname = :lastname, 
                                 email = :email,
                                 address = :address,
                                 birthday = :birthday,
                                 phone = :phone
                              WHERE id = :id';

      // Prepare statement
      $stmt = $this->db->connect()->prepare($query);

      // Clean data
      $this->cleanData();

      // Bind data
      $stmt->bindParam(':firstname', $this->firstname);
      $stmt->bindParam(':lastname', $this->lastname);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':address', $this->address);
      $stmt->bindParam(':birthday', $this->birthday);
      $stmt->bindParam(':phone', $this->phone);

      // Execute query
      if ($stmt->execute()) {
         return true;
      }

      throw new Exception("failed to execute update statement");
   }

   public function delete()
   {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->db->connect()->prepare($query);

      // Clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if ($stmt->execute()) {
         return true;
      }

      throw new Exception("failed to execute delete statement");
   }

   private function cleanData()
   {
      $this->firstname = htmlspecialchars(strip_tags($this->firstname));
      $this->lastname = htmlspecialchars(strip_tags($this->lastname));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->address = htmlspecialchars(strip_tags($this->address));
      $this->birthday = htmlspecialchars(strip_tags($this->birthday));
      $this->phone = htmlspecialchars(strip_tags($this->phone));
   }
}
