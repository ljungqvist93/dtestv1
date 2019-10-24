<?php

ini_set('display_errors', 'On');

define ('APP_ROOT', __DIR__);
define ('BASE_URL', 'https://www.danielljungqvist.se');

$db = new PDO('mysql:host=127.0.0.1;dbname=danielljungqvist;charset=latin1', 'root', 'Daniel93');