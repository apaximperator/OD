<?php

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
    public function openRandomProduct()
    {
        $I = $this;
        $productsCount = $I->getElementsCountByCssSelector("li.product-item");
        $randomProductNumber = rand(0, $productsCount - 1);
        $I->waitForElementClickable("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 10);
        $productLink = $I->grabAttributeFrom("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 'href');
        $productLink = str_replace(Credentials::$URL, '', $productLink);
        $I->click("//*[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']");
        $I->waitPageLoad();
        $I->seeInCurrentUrl($productLink);
        $I->waitForElementVisible("h1.page-title", 30);
    }

    /**
     * @throws Exception
     */
    public function sortBySelect()
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#sorter", 10);
        $sortCount = $I->getElementsCountByCssSelector("#sorter>option");
        for ($optionByIndex = 0; $optionByIndex < $sortCount / 2; $optionByIndex++) {
            $sortByOption = trim($I->executeJS("return document.querySelectorAll(\"#sorter option\")[$optionByIndex].innerText"));
            $I->selectOption("//select[@id='sorter']", $sortByOption);
            $I->waitPageLoad();
            $I->wait(1);
            $I->waitForElementVisible("select[id='sorter'] option:nth-of-type(" . ($optionByIndex + 1) . ")[selected='selected']", 10);
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
        $I->waitAjaxLoad();
        $filterName = $I->executeJS("return document.querySelector(\"div[aria-selected='true']\").innerText;");
        if ($filterName == 'COLOR') {
            $I->waitForElementVisible('div[aria-hidden = "false"] form div.item', 10);
            $filtersCount = $I->getElementsCountByCssSelector('div[aria-hidden = "false"] form div.item');
            $randomFilterNumber = rand(1, $filtersCount);
            $I->waitForElementClickable("div[aria-hidden = false] form div.item:nth-of-type($randomFilterNumber) a", 10);
            $I->executeJS("document.querySelector('div[aria-hidden = false] form div.item:nth-of-type($randomFilterNumber) a').click()");
        } else {
            $I->waitForElementVisible('div[aria-hidden = "false"] li.item', 10);
            $filtersCount = $I->getElementsCountByCssSelector('div[aria-hidden = "false"] li.item');
            $randomFilterNumber = rand(1, $filtersCount);
            $I->waitForElementClickable("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)", 10);
            $I->executeJS("document.querySelector('div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)').click()");
        }
        $I->waitAjaxLoad();
        $I->waitForElementVisible("a.action.clear.filter-clear", 10);
    }

    /**
     * @throws Exception
     */
    public function clearFilter()
    {
        $I = $this;
        $I->executeJS('window.scrollTo(0,0);');
        $I->waitForElementClickable("a.action.clear.filter-clear", 10);
        $I->click("a.action.clear.filter-clear");
        $I->waitForElementNotVisible("a.action.clear.filter-clear", 10);
    }

    /**
     *
     */
    public function openRandomCategoryWithPagination()
    {
        $I = $this;
        $I->connectJq();
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $I->openRandomCategoryBySelector('div>div.menu-column li ul a');
            try {
                $I->seeElement(".bss-bt-quickview");
                $I->seeElement(".pages-item-next a");
                $categoryWithoutProducts = false;
            } catch (Exception $e) {
                $categoryWithoutProducts = true;
            }
        }
    }
}