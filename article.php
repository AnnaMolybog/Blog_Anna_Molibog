<?php
session_start();

require "utils/database.php";
require "models/all.php";

$connection = connect('blog');
$categories = getCategory($connection);
$currentArticle = getCurrentArticle($connection, $_GET['id']);

include "view/article.phtml";