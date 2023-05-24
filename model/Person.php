<?php

namespace T2i\Model;

use PDO;

class Person
{
   private $db;
   private $table = 'person';

   function __construct($db)
   {
      $this->db = $db;
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

}
