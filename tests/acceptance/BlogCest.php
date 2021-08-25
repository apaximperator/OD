<?php

class BlogCest
{

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function blog(GlobalTester $G, CategoryTester $C)
    {
        $G->amOnPage('/');
        $G->connectJq();
//        $C->openRandomNotEmptyBrandCategory();
//        $user = $G->registration('test', 'test', 'test09@test.com', 'Test1234');
//        $G->logout();
//        $G->login($user['firstname'], $user['email'], $user['password']);
        $G->wait(5);
    }

}
