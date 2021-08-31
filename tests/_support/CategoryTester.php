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
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $I->openRandomCategoryBySelector('div>div.menu-column li.parent>a');
            try {
                $I->seeElement(".bss-bt-quickview");
                $categoryWithoutProducts = false;
            } catch (Exception $e) {
                $categoryWithoutProducts = true;
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
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $I->openRandomCategoryBySelector('div>div.menu-column li ul a');
            try {
                $I->seeElement(".bss-bt-quickview");
                $categoryWithoutProducts = false;
            } catch (Exception $e) {
                $categoryWithoutProducts = true;
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
    public function sortBySelect()
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#sorter");
        $sortCount = $I->getElementsCountByCssSelector("#sorter>option");
        for ($optionByIndex = 0; $optionByIndex < $sortCount / 2; $optionByIndex++) {
            $sortByOption = trim($I->executeJS("return document.querySelectorAll(\"#sorter option\")[$optionByIndex].innerText"));
            $I->selectOption("//select[@id='sorter']", $sortByOption);
            $I->waitPageLoad();
            $I->wait(1);
            $I->waitForElementVisible("select[id='sorter'] option:nth-of-type(" . ($optionByIndex + 1) . ")[selected='selected']");
        }
    }

    /**
     * @throws Exception
     */
    public function selectRandomFilter()
    {
        $I = $this;
        $I->connectJq();
        $dropdownFiltersCount = $I->getElementsCountByCssSelector(".filter-options-title");
        $randomDropdownFilter = rand(0, $dropdownFiltersCount - 1);
        $I->executeJS("document.querySelectorAll(\".filter-options-title\")[$randomDropdownFilter].click()");
        $I->waitForElementVisible('div[aria-hidden = "false"] li.item');
        $filtersCount = $I->getElementsCountByCssSelector('div[aria-hidden = "false"] li.item');
        $randomFilterNumber = rand(1, $filtersCount);
        $I->waitForElementClickable("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)", 10);
        $I->click("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)");
        $I->waitForElementVisible("a.action.clear.filter-clear");
        $I->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function clearFilter()
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("a.action.clear.filter-clear", 10);
        $I->click("a.action.clear.filter-clear");
        $I->waitForElementNotVisible("a.action.clear.filter-clear", 10);
    }

}