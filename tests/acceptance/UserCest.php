<?php

class UserCest
{
    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function userLogin(GlobalTester $G)
    {
        $G->amOnPage('/');
        $G->login();
        $G->logout();
    }

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function userRegister(GlobalTester $G)
    {
        $G->amOnPage('/');
        $user = $G->registration();
        $G->login($user['firstname'], $user['email'], $user['password']);
        $G->logout();
    }
}
