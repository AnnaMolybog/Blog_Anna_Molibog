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


if(!empty($_GET['id_edit'])) { //редактирование
    $currentArticle = getCurrentArticle($connection, @$_GET['id_edit']);
    $currentTag = getCurrentTag($connection, @$_GET['id_edit']);
    $currentTagFormat = [];
    foreach ($currentTag as $value) {
        $currentTagFormat[] = $value['tag_name'];
    }

    if(!empty($currentTag)) {
        $currentTagStr ='#' . implode("#", $currentTagFormat);
    } else {
        $currentTagStr = '';
    }
    include "view/editArticle.phtml";
    die();
}


if(!empty($_GET['id_delete'])) { //удаление
    $currentArticle = getCurrentArticle($connection, @$_GET['id_delete']);
    include "view/deleteArticle.phtml";
    die();
}

if(isset($_POST['edit'])) {//редактирование
    $articleId = $_POST['id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $content = $_POST['content'];
    $image = $_POST['image'];
    $categoryId = $_POST['id_category'];
    $editArticle = editArticle($connection, $articleId, $title, $date, $content, $image, $categoryId);

    $tagId = checkTag($connection, $_POST['tag']); // возвращает массив с айдишниками тегов

    foreach ($tagId as $tag) {
        $getTagArticle = getCurrentTagArticle($connection, $articleId, $tag[0]['id_tag']);

        if (empty($getTagArticle)) {
           newTagArticle($connection, $articleId, $tag[0]['id_tag']);
        }
    }
    $getTagArticleId = getTagArticleId($connection, $articleId);

    foreach ($getTagArticleId as $item) {
        $flag = 0;
        foreach ($tagId as $tag) {
            if ($item['id_tag'] == $tag[0]['id_tag']) {
                $flag += 1;
                break;
            }
            if ($flag == 1) {
                break;
            }
        }
        if ($flag == 0) {
            deleteTagArticle($connection, $item['id_tag_article']);
        }
    }

    if ($editArticle) {
        echo "Новая статья добавлена";
        header("Location: admin.php");
    } else {
        echo "Убедитесь что все поля заполнены";
    }
}

if(isset($_POST['delete'])) {//удаление
    echo "delete";
    echo $_POST['id'];
    $deleteArticle = deleteArticle($connection, $_POST['id']);
    echo $deleteArticle;
    if ($deleteArticle) {
        echo "Статья удалена добавлена";
        header("Location: admin.php");
    }
}

if(isset($_POST['add'])) {
    $newArticle = newArticle($connection, $_POST['title'], $_POST['date'], $_POST['content'], $_POST['image'], $_POST['id_category']);

    $tagId = checkTag($connection, $_POST['tag']);

    $lastArticleId = getArticleId($connection);

    foreach ($tagId as $tag) {
        $newTagArticle = newTagArticle($connection, $lastArticleId[0]['id'], $tag[0]['id_tag']);
    }

    if ($newArticle) {
        echo "Новая статья добавлена";
        header("Location: admin.php");
    } else {
        echo "Убедитесь что все поля заполнены";
    }
}
if (isset($_SESSION['user_logged_in']) && 1 == $_SESSION['user_logged_in']) {
    include "view/admin.phtml";
} else {
    include "view/denied.phtml";
}
close($connection);