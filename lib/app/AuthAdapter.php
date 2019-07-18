<?php
namespace app;

class AuthAdapter implements Aura\Auth\Adapter\AdapterInterface {

    public function login(array $input) {
        if (!array_key_exists('username', $input)) throw new \Aura\Auth\Exception\UsernameMissing();
        if (!array_key_exists('password', $input)) throw new \Aura\Auth\Exception\PasswordMissing();
        if ($input['username'] != 'joe') throw new \Aura\Auth\Exception\UsernameNotFound();
        if ($input['password'] != '123') throw new \Aura\Auth\Exception\PasswordIncorrect();

        return [$input['username'], []];
    }
    public function logout(\Aura\Auth\Auth $auth, $status = \Aura\Auth\Status::ANON) {
        // Intentionally blank
    }
    public function resume(\Aura\Auth\Auth $auth) {
        // Intentionally blank
    }
}