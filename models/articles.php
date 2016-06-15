<?php

function getArticles ($connection, $categoryId, $tagId, $page) {
    if(!isset($page)) {
        $page = 1;
    }
    $offset = $page * ARTICLES_PER_PAGE - ARTICLES_PER_PAGE;
    $sql = 'SELECT *
            FROM
              articles as a
              JOIN category AS c ON c.id_category = a.id_category
              JOIN tag_article AS ta ON ta.id_article = a.id
              JOIN tags AS t ON t.id_tag = ta.id_tag';
    if(isset($categoryId) && isset($tagId)) {
        $sql .= ' WHERE c.id_category = ' . $categoryId . ' AND ' . 't.id_tag = ' . $tagId;
    }
    if(isset($categoryId) && !isset($tagId)) {
        $sql .= ' WHERE c.id_category = ' . $categoryId;
    }

    if (!isset($categoryId) && isset($tagId)) {
        $sql .= ' WHERE t.id_tag = ' . $tagId;
    }

    $sql .=' GROUP BY a.id
             ORDER BY `date` DESC
             LIMIT ' . $offset . ", ". ARTICLES_PER_PAGE;
    //echo $sql; die();
    return query($sql, $connection);
}

function getCurrentArticle($connection, $id) {
    $sql = "SELECT *
            FROM
              articles
            WHERE articles.id=$id";
    return query($sql, $connection);
}

function getArticleId($connection) {
    $sql = 'SELECT MAX(id) AS id FROM articles';
    return query($sql, $connection);
}

function formatArticle ($text, $len = ARTICLE_LENGTH_MAIN_PAGE) {
    return mb_substr($text, 0, $len);
}

function newArticle($connection, $title, $date, $content, $image, $categoryId) {
    $title = trim($title);
    $content = trim($content);
    $image = trim($image);
    $categoryId = trim($categoryId);

    if ($title == '' || $date == '' || $content == '' || $image == '' || $categoryId == '') {
        return false;
    }

    $sql = "INSERT INTO articles (title, date, content, image, id_category) VALUES ('%s', '%s', '%s', '%s', '%s')";
    $sql = sprintf($sql, mysqli_real_escape_string($connection, $title), mysqli_real_escape_string($connection, $date),
                        mysqli_real_escape_string($connection, $content), mysqli_real_escape_string($connection, $image),
                        mysqli_real_escape_string($connection,$categoryId));

    $result = query($sql,$connection);

    if($result) {
        return true;
    } else {
        return false;
    }
}

function editArticle($connection, $articleId, $title, $date, $content, $image, $categoryId) {
    if ($title == '' || $date == '' || $content == '' || $image == '' || $categoryId == '') {
        return false;
    }
    $sql = sprintf("UPDATE articles SET title='%s', date='%s', content='%s', image='%s', id_category='%s' WHERE id = %d", $title, $date, $content, $image, $categoryId, $articleId);
    $result = query($sql,$connection);
    if($result) {
        return true;
    } else {
        return false;
    }

}

function deleteArticle($connection, $articleID) {
    if($articleID == 0) {
        return false;
    }
    $sql = sprintf("DELETE FROM articles WHERE id = '%d'", $articleID);
    $result = query($sql, $connection);
    if($result) {
        return true;
    } else {
        return false;
    }

}

function articlesCount($connection, $categoryId) {
    $sql = "SELECT COUNT(*) AS total_count FROM articles";
    if(isset($categoryId)) {
        $sql .= " WHERE id_category = $categoryId";
    }
    $result = query($sql, $connection);
    return $result[0]['total_count'];
}