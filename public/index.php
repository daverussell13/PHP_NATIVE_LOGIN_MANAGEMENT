<?php

require_once __DIR__ . "/../vendor/autoload.php";

if (isset($_SERVER["PATH_INFO"])) echo $_SERVER["PATH_INFO"];
