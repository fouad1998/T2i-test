<?php
$serverTimeZone = date_default_timezone_get();
echo json_encode(array("timezone" => $serverTimeZone));
