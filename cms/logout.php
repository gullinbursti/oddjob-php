<?php

session_start();

// start the output buffer
ob_start();

unset($_SESSION['login']);
session_destroy();

header('Location: ./login.php');

?>