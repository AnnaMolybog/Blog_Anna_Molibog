<?php
session_start();
unset($_SESSION['user_logged_in']);

header("Location: index.php");