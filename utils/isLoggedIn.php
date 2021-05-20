<?php
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

            
    if (!isset($_SESSION['username'])) {
        ob_start(); 
        header("Location: /rgz/login.php");
        ob_end_flush();
    }
?>