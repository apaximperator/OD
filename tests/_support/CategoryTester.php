<?php

use Faker\Factory;
use Page\Credentials;

class CategoryTester extends AcceptanceTester
{

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyBrandCategory()
    {
        $I = $this;
        $I->connectJq();
        $brandCategoryWithoutProducts = true;
        while ($brandCategoryWithoutProducts) {
            $I->openRandomBrandCategory();
            try {
                $I->seeElement("//div[@class='product-item-info']");
                $brandCategoryWithoutProducts = false;
            } catch (Exception $e) {
                $brandCategoryWithoutProducts = true;
            }
        }
    }

    /**
     *
     */
    private function openRandomBrandCategory()
    {
        $I = $this;
        $I->connectJq();
        $I->moveMouseOver("//span[contains(text(),'Brand')]/ancestor::a");
        $BrandCategoryCount = $I->getElementsCountByCssSelector('.menu-brand-block-content div .pagebuilder-column');
        $BrandCategoryNumber = rand(0, $BrandCategoryCount - 1);
        $BrandLink = $I->executeJS('return document.querySelectorAll(".menu-brand-block-content div .pagebuilder-column figure a")[' . $BrandCategoryNumber . '].getAttribute("href");');
        $I->executeJS('document.querySelectorAll(".menu-brand-block-content div .pagebuilder-column figure a")[' . $BrandCategoryNumber . '].click();');
        $I->waitPageLoad();
        $I->canSeeInCurrentUrl($BrandLink);
    }

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyCLP()
    {
        $I = $this;
        $I->connectJq();
        $categoryWithoutProducts = true; //Creating a variable for check 'Select Your Items' button
        while ($categoryWithoutProducts) { //Start cycle for check 'Select Your Items' button
            $I->openRandomCategoryBySelector('div>div.menu-column li.parent>a'); //Open random category with check 'Select Your Items' button
            try {
                $I->seeElement(".bss-bt-quickview"); //Check product with 'Select Your Items' button availability
                $categoryWithoutProducts = false; //This category without products with 'Select Your Items' button - false
            } catch (Exception $e) {
                $categoryWithoutProducts = true; //This category without products with 'Select Your Items' button - true
            }
        }
    }

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyPLP()
    {
        $I = $this;
        $I->connectJq();
        $categoryWithoutProducts = true; //Creating a variable for check 'Select Your Items' button
        while ($categoryWithoutProducts) { //Start cycle for check 'Select Your Items' button
            $I->openRandomCategoryBySelector('div>div.menu-column li ul a'); //Open random category with check 'Select Your Items' button
            try {
                $I->seeElement(".bss-bt-quickview"); //Check product with 'Select Your Items' button availability
                $categoryWithoutProducts = false; //This category without products with 'Select Your Items' button - false
            } catch (Exception $e) {
                $categoryWithoutProducts = true; //This category without products with 'Select Your Items' button - true
            }
        }
    }

    /**
     * @param string $selector
     */
    private function openRandomCategoryBySelector(string $selector)
    {
        $I = $this;
        $I->connectJq();
        $I->moveMouseOver("//span[contains(text(),'Women')]/ancestor::a");
        $CategoryCount = $I->getElementsCountByCssSelector($selector);
        $CategoryNumber = rand(0, $CategoryCount - 1);
        $CategoryLink = $I->executeJS('return document.querySelectorAll("' . $selector . '")[' . $CategoryNumber . '].getAttribute("href");');
        $CategoryLink = str_replace(Credentials::$URL, '', $CategoryLink);
        $I->executeJS('document.querySelectorAll("' . $selector . '")[' . $CategoryNumber . '].click();');
        $I->waitPageLoad();
        $I->canSeeInCurrentUrl($CategoryLink);
    }

    /**
     * @throws Exception
     */
    public function openSupplementStacksCategory() //The category with 'select your items' products
    {
        $I = $this;
        $I->click("//li[contains(@class,'level0 nav')][2]"); //Open 'Shop by category' category
        $I->wait(1);
        $I->click("//ul[contains(@class,'is-active')]//li[contains(@class,'level1')]//a[contains(@href,'/supplement-stacks')]"); //Click on 'supplement-stacks' category
        $I->waitPageLoad();
        $I->waitForElementVisible("//h1[@id='page-title-heading']", 30); //Waiting for category h1 title
    }

    /**
     * @throws Exception
     */
    public function openRandomProduct() //Check product qty and open random product with this qty
    {
        $I = $this;
        $productsCount = (int)$I->grabTextFrom("//div[@class='toolbar-top']//span[@class='toolbar-number']"); //Check and converted to int products qty
        if ($productsCount > 32) { //If products qty is over 32
            $productsCount = 32; //Then write '32' number to variable
        }
        $randomProductNumber = Factory::create(); //Run Faker create generator
        $randomProductNumber = $randomProductNumber->numberBetween(1, $productsCount); //Converted to string and generate numberBetween
        $I->waitForElementClickable("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']"); //Waiting for product item link is clickable
        $I->wait(1);
        $I->click("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product photo product-item-photo']"); //Click on random product card
        $I->waitPageLoad();
        $I->waitForElementVisible("h1.page-title", 30); //Waiting for product h1 title
    }

    /**
     * @throws Exception
     */
    public function sortBySelect() //Get 'Sort By' option count and select each
    {
        $I = $this;
        $I->waitForElementVisible("//div[@class='toolbar-top']//select[@id='sorter']"); //Check 'Sort By' select
        $sortCount = $I->getElementsCountByCssSelector('div.toolbar-top select#sorter option'); //Writing variable with sort by options count
        for ($optionByIndex = 1; $optionByIndex <= $sortCount; $optionByIndex++) { //Start cycle for 'Sort By' select
            $sortByOption = $I->grabTextFrom("//div[@class='toolbar-top']//select[@id='sorter']//option[$optionByIndex]"); //Writing variable with desired sort by option
            $I->selectOption("//div[@class='toolbar-top']//select[@id='sorter']", $sortByOption); //Select desired sort by option
            $I->waitPageLoad(); //Waiting for full page load
            $I->waitForElementVisible("div [class='toolbar-top'] select[id='sorter'] option:nth-of-type($optionByIndex)[selected='selected']"); //Check that the option is 'selected'
        }
    }


    /**
     * @throws Exception
     */
    public function selectRandomFilter() //TODO делать фильтр
    {
        $I = $this;
        $I->connectJq();
        $dropdownFiltersCount = $I->getElementsCountByCssSelector(".filter-options-title"); //Writing variable with dropdown filters count
        $randomDropdownFilter = rand(0, $dropdownFiltersCount - 1);
        $I->click(".filter-options-title'][$randomDropdownFilter]"); //Open random filter group dropdown
        $I->wait(1);
        $filterName = $I->executeJS("return document.querySelectorAll(\".filter-options-title\")[$randomDropdownFilter].textContent");

        $filtersCount = $I->getElementsCountByCssSelector('div[aria-hidden = "false"] li.item'); //Writing variable with filters count
        $randomFilterNumber = Factory::create(); //Run Faker create generator
        $randomFilterNumber = $randomFilterNumber->numberBetween(1, $filtersCount); //Converted to string and generate numberBetween
        $I->click("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber) a"); //Click on random filter
        $I->waitForElementVisible("//a[@class='action remove']"); //Waiting for the filter is selected
        $I->waitPageLoad();
    }

}