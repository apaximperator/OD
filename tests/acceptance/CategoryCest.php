<?php

class CategoryCest
{

    /**
     * @param CategoryTester $C
     * @throws Exception
     */
    public function filters(CategoryTester $C)
    {
        $C->amOnPage('/');
        $C->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30);
        $C->openRandomNotEmptyCLP();
        $C->selectRandomFilter();
        $C->clearFilter();
        $C->openRandomNotEmptyPLP();
        $C->selectRandomFilter();
        $C->clearFilter();
        $C->openRandomNotEmptyBrandCategory();
        $C->selectRandomFilter();
        $C->clearFilter();

    }

    /**
     * @param CategoryTester $C
     * @throws Exception
     */
    public function sortByOnCLP(CategoryTester $C)
    {
        $C->amOnPage('/');
        $C->openRandomNotEmptyCLP();
        $C->sortBySelect();
    }

    /**
     * @param CategoryTester $C
     * @throws Exception
     */
    public function sortByOnPLP(CategoryTester $C)
    {
        $C->amOnPage('/');
        $C->openRandomNotEmptyPLP();
        $C->sortBySelect();
    }

    /**
     * @param CategoryTester $C
     * @throws Exception
     */
    public function sortByOnBrand(CategoryTester $C)
    {
        $C->amOnPage('/');
        $C->openRandomNotEmptyBrandCategory();
        $C->sortBySelect();
    }

    //TODO Написать тест для пагинации
}
