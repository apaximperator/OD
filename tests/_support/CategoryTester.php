<?php

use Page\Credentials;

class CategoryTester extends AcceptanceTester
{

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyBrandCategory()
    {
        $C = $this;
        $C->connectJq();
        $brandCategoryWithoutProducts = true;
        while ($brandCategoryWithoutProducts) {
            $C->openRandomBrandCategory();
            try {
                $C->seeElement("//div[@class='product-item-info']");
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
        $C = $this;
        $C->connectJq();
        $C->moveMouseOver("//span[contains(text(),'Brand')]/ancestor::a");
        $BrandCategoryCount = $C->getElementsCountByCssSelector('.menu-brand-block-content div .pagebuilder-column');
        $BrandCategoryNumber = rand(0, $BrandCategoryCount - 1);
        $BrandLink = $C->executeJS('return document.querySelectorAll(".menu-brand-block-content div .pagebuilder-column figure a")[' . $BrandCategoryNumber . '].getAttribute("href");');
        $C->executeJS('document.querySelectorAll(".menu-brand-block-content div .pagebuilder-column figure a")[' . $BrandCategoryNumber . '].click();');
        $C->waitPageLoad();
        $C->canSeeInCurrentUrl($BrandLink);
    }

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyCLP()
    {
        $C = $this;
        $C->connectJq();
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $C->openRandomCategoryBySelector('div>div.menu-column li.parent>a');
            try {
                $C->seeElement(".bss-bt-quickview");
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
        $C = $this;
        $C->connectJq();
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $C->openRandomCategoryBySelector('div>div.menu-column li ul a');
            try {
                $C->seeElement(".bss-bt-quickview");
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
        $C = $this;
        $C->connectJq();
        $C->moveMouseOver("//span[contains(text(),'Women')]/ancestor::a");
        $CategoryCount = $C->getElementsCountByCssSelector($selector);
        $CategoryNumber = rand(0, $CategoryCount - 1);
        $CategoryLink = $C->executeJS('return document.querySelectorAll("' . $selector . '")[' . $CategoryNumber . '].getAttribute("href");');
        $CategoryLink = str_replace(Credentials::$URL, '', $CategoryLink);
        $C->executeJS('document.querySelectorAll("' . $selector . '")[' . $CategoryNumber . '].click();');
        $C->waitPageLoad();
        $C->canSeeInCurrentUrl($CategoryLink);
    }

    /**
     * @throws Exception
     */
    public function openRandomProduct()
    {
        $C = $this;
        $productsCount = $C->getElementsCountByCssSelector("li.product-item");
        $randomProductNumber = rand(0, $productsCount - 1);
        $C->waitForElementClickable("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 10);
        $productLink = $C->grabAttributeFrom("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 'href');
        $productLink = str_replace(Credentials::$URL, '', $productLink);
        $C->click("//*[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']");
        $C->waitPageLoad();
        $C->seeInCurrentUrl($productLink);
        $C->waitForElementVisible("h1.page-title", 30);
    }

    /**
     * @throws Exception
     */
    public function sortBySelect()
    {
        $C = $this;
        $C->connectJq();
        $C->waitForElementVisible("#sorter", 10);
        $sortCount = $C->getElementsCountByCssSelector("#sorter>option");
        for ($optionByIndex = 0; $optionByIndex < $sortCount / 2; $optionByIndex++) {
            $sortByOption = trim($C->executeJS("return document.querySelectorAll(\"#sorter option\")[$optionByIndex].innerText"));
            $C->selectOption("//select[@id='sorter']", $sortByOption);
            $C->waitPageLoad();
            $C->wait(1);
            $C->waitForElementVisible("select[id='sorter'] option:nth-of-type(" . ($optionByIndex + 1) . ")[selected='selected']", 10);
        }
    }

    /**
     * @throws Exception
     */
    public function selectRandomFilter()
    {
        $C = $this;
        $C->connectJq();
        $dropdownFiltersCount = $C->getElementsCountByCssSelector(".filter-options-title");
        $randomDropdownFilter = rand(0, $dropdownFiltersCount - 1);
        $C->executeJS("document.querySelectorAll(\".filter-options-title\")[$randomDropdownFilter].click()");
        $C->waitAjaxLoad();
        $filterName = $C->executeJS("return document.querySelector(\"div[aria-selected='true']\").innerText;");
        if ($filterName == 'COLOR') {
            $C->waitForElementVisible('div[aria-hidden = "false"] form div.item', 10);
            $filtersCount = $C->getElementsCountByCssSelector('div[aria-hidden = "false"] form div.item');
            $randomFilterNumber = rand(1, $filtersCount);
            $C->waitForElementClickable("div[aria-hidden = false] form div.item:nth-of-type($randomFilterNumber) a", 10);
            $C->executeJS("document.querySelector('div[aria-hidden = false] form div.item:nth-of-type($randomFilterNumber) a').click()");
        } else {
            $C->waitForElementVisible('div[aria-hidden = "false"] li.item', 10);
            $filtersCount = $C->getElementsCountByCssSelector('div[aria-hidden = "false"] li.item');
            $randomFilterNumber = rand(1, $filtersCount);
            $C->waitForElementClickable("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)", 10);
            $C->executeJS("document.querySelector('div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber)').click()");
        }
        $C->waitAjaxLoad();
        $C->waitForElementVisible("a.action.clear.filter-clear", 10);
    }

    /**
     * @throws Exception
     */
    public function clearFilter()
    {
        $C = $this;
        $C->executeJS('window.scrollTo(0,0);');
        $C->waitForElementClickable("a.action.clear.filter-clear", 10);
        $C->click("a.action.clear.filter-clear");
        $C->waitForElementNotVisible("a.action.clear.filter-clear", 10);
    }

    /**
     *
     */
    public function openRandomCategoryWithPagination()
    {
        $C = $this;
        $C->connectJq();
        $categoryWithoutProducts = true;
        while ($categoryWithoutProducts) {
            $C->openRandomCategoryBySelector('div>div.menu-column li ul a');
            try {
                $C->seeElement(".bss-bt-quickview");
                $C->seeElement(".pages-item-next a");
                $categoryWithoutProducts = false;
            } catch (Exception $e) {
                $categoryWithoutProducts = true;
            }
        }
    }
}