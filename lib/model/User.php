<?php
namespace model;

class User {
    public static function checkCredentials($credentials) {
        list($username, $password) = [$credentials['username'], $credentials['password']];
        if ($username == 'joe' && $password == '123') return 'joe';
    }
    public static function dbRead() {
        $result = new User;
        $result->username = 'joe';
        return $result;
    }
    
}