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
        $C->waitPageLoad(10);
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
        $C->waitPageLoad(10);
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
        $C->waitPageLoad(10);
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
        $C->waitPageLoad(10);
        $C->openRandomNotEmptyBrandCategory();
        $C->sortBySelect();
    }

    /**
     * @param CategoryTester $C
     */
    public function pagination(CategoryTester $C)
    {
        $C->amOnPage('/');
        $C->waitPageLoad(10);
        $C->openRandomCategoryWithPagination();
        $C->executeJS('document.querySelectorAll(".pages-item-next > a")[1].click()');
        $C->waitPageLoad(10);
        $C->seeElement('.pages-item-previous a');
        $C->executeJS('document.querySelectorAll(".pages-item-previous > a")[1].click()');
        $C->waitPageLoad(10);
        $C->dontSee('.pages-item-previous a');
    }
}
