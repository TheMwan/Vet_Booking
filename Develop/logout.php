<?php
session_start();
session_destroy();
header('Location: dev-login.html');
?>
