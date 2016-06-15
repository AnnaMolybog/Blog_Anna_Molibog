<?php
function isValidUser($connection, $login, $password) {
    $sql = sprintf('SELECT id_user, user_login, user_password FROM `user` WHERE user_login = \'%s\' AND user_password = \'%s\'', $login, md5($password));
    $result = query($sql, $connection);

    return !empty($result);
}