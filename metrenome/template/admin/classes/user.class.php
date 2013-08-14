<?php

class User {
    public function isLoggedIn() {
        return !empty($_SESSION['is_logged_in']);
    }

    public function logIn() {
        return $_SESSION['is_logged_in'] = true;
    }

    public function logOut() {
        unset($_SESSION['is_logged_in']);
    }

    public function checkPassword($password) {
        return ($password == Core::get()->config['password']);
    }
}

?>