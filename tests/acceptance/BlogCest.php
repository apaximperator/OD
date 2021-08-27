<?php

class BlogCest
{

    /**
     * @param GlobalTester $G
     * @param CategoryTester $C
     * @throws Exception
     */
    public function blog(GlobalTester $G, CategoryTester $C)
    {
        $G->amOnPage('/');
        $G->searchByText();
    }

}
