<?php
function getCategory($connection) {
    $sql = 'SELECT * FROM category';
    return query($sql, $connection);
}

function getCurrentCategory($connection, $categoryId) {
    $sql = "SELECT * FROM category WHERE id_category=$categoryId";
    return query($sql, $connection);
}