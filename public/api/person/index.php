<?php

require_once "../../../vendor/autoload.php";


use T2i\Config\Database;
use T2i\Model\Person;


$uri = $_SERVER["REQUEST_URI"];
$uri = str_replace("/api/person", "", $uri);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$db = new Database();

$person = new Person($db);

try {
   switch ($requestMethod) {
      case "GET":
         if ($uri == "") {
            $response = $person->getAll();
            echo json_encode($response);
            return;
         }

         echo $uri;
         break;
     
      default:
         echo "Error: method not supported";
         http_response_code(405);
   }
} catch (Exception $th) {
   http_response_code(500);
   echo json_encode(array("error" => $th));
}
