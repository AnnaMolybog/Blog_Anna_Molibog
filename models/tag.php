<?php

function getTag($connection) {

        $sql = 'SELECT * FROM tags';

    return query($sql, $connection);
}

function getCurrentTag($connection, $articleId) {
    $sql = 'SELECT *
            FROM tags AS t
            JOIN tag_article AS ta ON ta.id_tag = t.id_tag
            WHERE ta.id_article=' . $articleId;
    return query($sql, $connection);
}

function getTagId ($connection, $tagName) {
    $sql = 'SELECT id_tag FROM tags WHERE tag_name=' . $tagName;
    return query($sql, $connection);
}

function newTag($connection, $tagName) {
    $sql = sprintf("INSERT INTO tags (tag_name) VALUES ('%s')", $tagName);

    $result = query($sql, $connection);
    if($result) {
        return true;
    } else {
        return false;
    }
}

function deleteTagArticle($connection, $tagArticleId) {
    $sql = sprintf("DELETE FROM tag_article WHERE id_tag_article = '%d'", $tagArticleId);;
    $result = query($sql, $connection);
    if($result) {
        return true;
    } else {
        return false;
    }
}

function getTagArticleId($connection, $articleId) {
    $sql = 'SELECT * FROM tag_article WHERE id_article=' . $articleId;

    return query($sql, $connection);
}

function getCurrentTagArticle ($connection, $articleId, $tagId) {
    $sql = 'SELECT * FROM tag_article WHERE id_article=' . $articleId . ' AND id_tag=' . $tagId;

    return query($sql, $connection);
}



function newTagArticle ($connection, $idArticle, $idTag) {
    $sql = sprintf("INSERT INTO tag_article (id_article, id_tag) VALUES ('%d', '%d')", $idArticle, $idTag);

    $result = query($sql, $connection);
    if($result) {
        return true;
    } else {
        return false;
    }
}

function checkTag ($connection, $tags){
    $tagInput = explode('#', $tags);
    unset($tagInput[0]);
    $allTag = getTag($connection);
    $tagId = [];
    foreach ($tagInput as $value) {
        $flag = 0;
        foreach ($allTag as $tag) {
            if($value == $tag['tag_name']) {
                $flag += 1;
                break;
            }
            if($flag == 1) {
                break;
            }
        }
        if($flag == 0) {
            newTag($connection, $value);
        }
        $tagId[] = getTagId($connection, "'$value'");
    }
    return $tagId;
}


