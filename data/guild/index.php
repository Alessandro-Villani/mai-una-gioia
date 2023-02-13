<?php
$url = 'https://armory.warmane.com/api/guild/Mai+Una+Gioia/Icecrown/summary';

$string = file_get_contents($url);



header('Content-type: application/json');

//Sending
echo ($string);
