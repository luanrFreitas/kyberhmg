<?php
error_reporting(0);
session_name('gdcon');
session_start();

include ("config.php");

require 'exportMysqlToCsv.php';

exportMysqlToCsv("`chars`", "chars.csv");

?>