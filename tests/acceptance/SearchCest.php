<?php

class SearchCest

{
    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function instantSearch(GlobalTester $G)
    {
        $G->amOnPage("/");
        $G->instantSearchByText('bra');
        $G->instantSearchEmptyResult();
    }

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function search(GlobalTester $G)
    {
        $G->amOnPage("/");
        $G->searchByText();
        $G->searchEmptyResult();
    }
}
