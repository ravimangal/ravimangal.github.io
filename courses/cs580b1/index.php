<?php
   // The PHP header command used in this way will redirect
   // client browswers to the directed named by variable term
   $host  = $_SERVER['HTTP_HOST'];
   $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
   $term = '2024fa';
   header("Location: http://$host$uri/$term");
   exit;
?>
