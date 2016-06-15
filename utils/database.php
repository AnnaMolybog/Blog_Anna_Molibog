<?php

function connect($database) {
    $link = mysqli_connect('Localhost', 'root', '') or die("Could not connect: " . mysqli_error($link));
    mysqli_query($link, "SET NAMES utf8");
    mysqli_select_db($link, $database);
    return $link;
}

function query($sql, $link) {
    $result = mysqli_query($link, $sql) or die("Query failed: " . mysqli_error($link));
    if ($result === true) {
        return $result;
    }
    if ($result === false) {
        return [];
    }
    $data = [];
    while ($line = mysqli_fetch_array($result)) {
        $data[] = $line;
    }
    mysqli_free_result($result);
    return $data;
}

function close($link) {
    mysqli_close($link);
}
