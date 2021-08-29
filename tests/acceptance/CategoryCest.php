<?php

class CategoryCest
{

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function filters(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->openRandomNotEmptyCLP();
        $I->selectRandomFilter();
        $I->click("//a[contains(@class,'action clear')]"); //Click on 'clear filters' button
        $I->waitForElementNotVisible("//a[contains(@class,'action clear')]"); //Waiting for 'clear filters' button to be not visible
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function sortBy(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->openRandomNotEmptyCategory();
        $I->sortBySelect(); //Check 'Sort By' select
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function lazyLoad(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $loopCondition = true; //Creating a variable for an empty category & and category without lazy load
        while ($loopCondition) { //Start cycle for check empty category & and category without lazy load
            $I->openRandomNotEmptyCategory();
            try {
                $I->dontSeeElement("//div[@class='message info empty']"); //Check empty category message
                $loopCondition = false; //This category is empty - false
            } catch (Exception $e) {
                $loopCondition = true; //This category is empty - true
            }
            if ($loopCondition === false) { //If category is not empty
                $productsCount = (int)$I->grabTextFrom("//div[@class='toolbar-top']//span[@class='toolbar-number']"); //Check and converted to int products qty
                if ($productsCount <= 32) { //If products qty is less/equal to 32
                    $loopCondition = true; //All loop conditions are true - end the loop
                }
            }
        }
        $I->scrollTo("//div[@class='toolbar-bottom']//span[contains(text(),'32')]"); //Scroll to bottom of the category with lazy load
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitPageLoad(); //Waiting for full page load
        $I->seeElement("//li[@class='item product product-item'][33]"); //Check 33th product is visible
    }

}
