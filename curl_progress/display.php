<?php
   session_start();
   echo json_encode(array("inen" => $_SESSION["inen"],"devam" => $_SESSION['devam'],"toplam" => $_SESSION['toplam']));


?>
