<?php

session_start();

require "utils/database.php";
require "models/all.php";

define('ARTICLE_LENGTH_MAIN_PAGE', 260);
define('ARTICLES_PER_PAGE', 5);

$connection = connect('blog');
$totalArticles = articlesCount($connection, @$_GET['id_category']);
$pages = ceil($totalArticles / ARTICLES_PER_PAGE);

$articles = getArticles($connection, @$_GET['id_category'], @$_GET['id_tag'], @$_GET['page']);

$categories = getCategory($connection);

$allTags = getTag($connection);

if(!empty($_POST)) {
    $isValidUser = isValidUser($connection, $_POST['user_login'], $_POST['user_password']);

    if($isValidUser) {
        $_SESSION['user_logged_in'] = 1;
        $_SESSION['user_login'] = $_POST['user_login'];
        header("Location: admin.php");
        die();
    } else {
        echo "Wrong login or password";
    }
}

include "view/index.phtml";