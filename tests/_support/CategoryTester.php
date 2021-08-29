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
            $I->openRandomCLP(); //Open random category with check 'Select Your Items' button
            try {
                $I->seeElement(".bss-bt-quickview"); //Check product with 'Select Your Items' button availability
                $categoryWithoutProducts = false; //This category without products with 'Select Your Items' button - false
            } catch (Exception $e) {
                $categoryWithoutProducts = true; //This category without products with 'Select Your Items' button - true
            }
        }
    }

    /**
     *
     */
    private function openRandomCLP()
    {
        $I = $this;
        $I->connectJq();
        $I->moveMouseOver("//span[contains(text(),'Women')]/ancestor::a");
        $CategoryCount = $I->getElementsCountByCssSelector('div.menu-column>li ul');
        $CategoryNumber = rand(0, $CategoryCount - 1);
        $CategoryLink = $I->executeJS('return document.querySelectorAll("div>div.menu-column li.parent>a")[' . $CategoryNumber . '].getAttribute("href");');
        $CategoryLink = str_replace(Credentials::$URL, '', $CategoryLink);
        $I->executeJS('document.querySelectorAll("div>div.menu-column li.parent>a")[' . $CategoryNumber . '].click();');
        $I->waitPageLoad();
        $I->canSeeInCurrentUrl($CategoryLink);
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
            $I->openRandomPLP(); //Open random category with check 'Select Your Items' button
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
    private function openRandomPLP()
    {
        $I = $this;
        $I->connectJq();
        $I->moveMouseOver("//span[contains(text(),'Women')]/ancestor::a");
        $CategoryCount = $I->getElementsCountByCssSelector('div>div.menu-column li ul a');
        $CategoryNumber = rand(0, $CategoryCount - 1);
        $CategoryLink = $I->executeJS('return document.querySelectorAll("div>div.menu-column li ul a")[' . $CategoryNumber . '].getAttribute("href");');
        $CategoryLink = str_replace(Credentials::$URL, '', $CategoryLink);
        $I->executeJS('document.querySelectorAll("div>div.menu-column li ul a")[' . $CategoryNumber . '].click();');
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
    public function openMostPopularCategory()
    {
        $I = $this;
        $topCategoryCount = $I->getElementsCountByCssSelector('li.level0.parent'); //Writing variable with top categories count
        $randomTopCategoryNumber = Factory::create(); //Run Faker create generator
        $randomTopCategoryNumber = $randomTopCategoryNumber->numberBetween(2, $topCategoryCount); //Converted to string and generate numberBetween
        $I->click("//li[contains(@class,'level0 nav')][$randomTopCategoryNumber]"); //Click on random top category
        $I->wait(1);
        $I->click("//li[contains(@class,'level0 nav')][$randomTopCategoryNumber]//a[contains(@class,'popular-all')]"); //Click on 'View all' button
        $I->waitPageLoad(); //Waiting for full page load
        $I->waitForElementVisible("//h1[@id='page-title-heading']", 30); //Waiting for category h1 title
    }

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyBrandByAlphabetCategory() //Open random brand category by alphabet with not empty check
    {
        $I = $this;
        $foundEmptyCategory = true; //Creating a variable for an empty category
        while ($foundEmptyCategory) { //Start cycle for check empty category
            $I->openRandomBrandByAlphabetCategory(); //Open random brand category by alphabet
            try {
                $I->dontSeeElement("//div[@class='message info empty']"); //Check empty category message
                $foundEmptyCategory = false; //This category is empty - false
            } catch (Exception $e) {
                $foundEmptyCategory = true; //This category is empty - true
            }
        }
    }

    /**
     * @throws Exception
     */
    public function openRandomBrandByAlphabetCategory()
    {
        $I = $this;
        $I->click("//li[contains(@class,'level0 nav-')][1]"); //Click on 'Shop by Brand' category
        $I->wait(1);
        $I->waitForElementVisible("//div[@class='js-alphabet-row alphabet-row']"); //Check alphabet row
        $alphabetLettersCount = $I->getElementsCountByCssSelector('div.alphabet-row a'); //Writing variable with alphabet letters count
        $randomBrandLetter = Factory::create(); //Run Faker create generator
        $randomBrandLetter = $randomBrandLetter->numberBetween(1, $alphabetLettersCount); //Converted to string and generate numberBetween
        $I->click("//div[contains(@class,'alphabet-row')]//a[$randomBrandLetter]"); //Click on random brand letter
        $alphabetSubCategoryCount = $I->getElementsCountByCssSelector('div[class = is-active] a'); //Writing variable with alphabet subcategory count
        $randomSubCategoryNumber = Factory::create(); //Run Faker create generator
        $randomSubCategoryNumber = $randomSubCategoryNumber->numberBetween(1, $alphabetSubCategoryCount); //Converted to string and generate numberBetween
        $I->click("(//div[@class='is-active']//ul[contains(@class,'brands')]//a)[$randomSubCategoryNumber]"); //Click on random subcategory number
        $I->waitPageLoad(); //Waiting for full page load
        $I->waitForElementVisible("//div[contains(@class,'brand')]//h1", 30); //Waiting for brand h1 title
    }

    /**
     * @throws Exception
     */
    public function openRandomNotEmptyBrandByImageCategory() //Open random brand category by image with not empty check
    {
        $I = $this;
        $foundEmptyCategory = true; //Creating a variable for an empty category
        while ($foundEmptyCategory) { //Start cycle for check empty category
            $I->openRandomBrandByImageCategory(); //Open random brand category by image
            try {
                $I->dontSeeElement("//div[@class='message info empty']"); //Check empty category message
                $foundEmptyCategory = false; //This category is empty - false
            } catch (Exception $e) {
                $foundEmptyCategory = true; //This category is empty - true
            }
        }
    }

    /**
     * @throws Exception
     */
    public function openRandomBrandByImageCategory()
    {
        $I = $this;
        $I->click("//li[contains(@class,'level0 nav-')][1]"); //Click on 'Shop by Brand' category
        $I->wait(1);
        $parentBrandWithImageCount = $I->getElementsCountByCssSelector('li[class = "level1 parent"]'); //Writing variable with brand with image count
        $randomParentBrandWithImage = Factory::create(); //Run Faker create generator
        $randomParentBrandWithImage = $randomParentBrandWithImage->numberBetween(1, $parentBrandWithImageCount); //Converted to string and generate numberBetween
        $I->click("//li[@class='level1 parent'][$randomParentBrandWithImage]"); //Click on random brand with image
        $brandWithImageCount = $I->getElementsCountByCssSelector('li.is-active div.brands-item'); //Writing variable with brand image count
        $randomBrandImage = Factory::create(); //Run Faker create generator
        $randomBrandImage = $randomBrandImage->numberBetween(1, $brandWithImageCount); //Converted to string and generate numberBetween
        $I->click("li.is-active div.brands-item:nth-of-type($randomBrandImage) a[class]"); //Click on random brand image
        $I->waitPageLoad(); //Waiting for full page load
        $I->waitForElementVisible("//div[contains(@class,'brand')]//h1", 30); //Waiting for brand h1 title
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
    public function openRandomProductWithAddToCartOrQuickAddButton() //Check product qty and open random product with 'Add to cart' or 'Quick add' button with this qty
    {
        $I = $this;
        $productsCount = (int)$I->grabTextFrom("//div[@class='toolbar-top']//span[@class='toolbar-number']"); //Check and converted to int products qty
        if ($productsCount > 32) { //If products qty is over 32
            $productsCount = 32; //Then write '32' number to variable
        }
        $randomProductNumber = Factory::create(); //Run Faker create generator
        $randomProductNumber = $randomProductNumber->numberBetween(1, $productsCount); //Converted to string and generate numberBetween
        $foundQuickAddOrAddToCartButton = false; //Creating a variable for 'Add to cart' or 'Quick add' button
        while (!$foundQuickAddOrAddToCartButton) { //Start cycle for check 'Add to cart' or 'Quick add' button
            try {
                $I->seeElement("//li[@class='item product product-item'][$randomProductNumber]//button[contains(@class,'action')][@title='Add to Cart' or 'Quick add']"); //Check 'Add to cart' or 'Quick add' button
                $foundQuickAddOrAddToCartButton = true; //This product has an 'Add to cart' or 'Quick add' button - true
                $I->waitForElementClickable("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']"); //Waiting for product item link is clickable
                $I->wait(1);
                $I->click("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product photo product-item-photo']"); //Click on product card with 'Add to cart' or 'Quick add' button
            } catch (Exception $e) {
                $randomProductNumber = Factory::create(); //Run Faker create generator
                $randomProductNumber = $randomProductNumber->numberBetween(1, $productsCount); //Converted to string and generate numberBetween
            }
        }
        $I->waitPageLoad(); //Waiting for full page load
        $I->waitForElementVisible("h1.page-title", 30); //Waiting for product h1 title
    }

    public function login()
    {
        $I = $this;
        $I->waitForElementVisible("//span[contains(text(), 'My Account')]/ancestor::div[contains(@class, 'links-primary')]/div/a[contains(@class,'link-account')]", 5);
        $I->click("//span[contains(text(), 'My Account')]/ancestor::div[contains(@class, 'links-primary')]/div/a[contains(@class,'link-account')]");
        try {
            $I->waitForElementVisible("//div[@id='tab-login' and @style='display: block;']"); //Waiting for the dropdown to open
            $I->waitForElementVisible("//a[contains(text(),'Logout')]", 3);
            $I->see("//a[contains(text(),'Logout')]");
        } catch (Exception $e) {
            $I->fillField("//div[@id='tab-login']//input[@name='username']", Credentials::$EMAIL); //Enter password
            $I->fillField("//div[@id='tab-login']//input[@name='password']", Credentials::$PASSWORD); //Enter password
            $I->click("//button[@id='bt-social-login']"); //Click to 'Sign In' button
            $I->waitForText('PLEASE WAIT...', 30, "//div[@class='mesg-request']"); //Waiting 'Please wait...' text
            $I->waitForText('WELCOME, AUTOMATION', 30, "//div[@class='box-dashboard__title']"); //Waiting 'Welcome..' text
            $I->seeInCurrentUrl('/customer/account/'); //Check in 'My account' URL
        }
    }

    /**
     * @throws Exception
     */
    public function openRandomProductWithSelectYourItemsButton()
    {
        $I = $this;
        $I->amOnPage('/shop-by-category/supplement-stacks');
        $productsCount = $I->getElementsCountByCssSelector("li.product-item a.tocart"); //Writing variable with products with 'Select your items' button
        $randomProductNumber = Factory::create(); //Run Faker create generator
        $randomProductNumber = $randomProductNumber->numberBetween(1, $productsCount); //Converted to string and generate numberBetween
        $I->waitForElementClickable("(//li[@class='item product product-item']//a[contains(@class,'action tocart')])[$randomProductNumber]"); //Waiting for product item link is clickable
        $I->wait(1);
        $I->click("(//li[@class='item product product-item']//a[contains(@class,'action tocart')])[$randomProductNumber]/ancestor::li[@class='item product product-item']//a[@class='product photo product-item-photo']"); //Click on random product card
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
     * @param $numberOfFilters
     * @throws Exception
     */
    public function selectAndRemoveRandomNumberOfFilters($numberOfFilters) //Select random filter and then remove that selected filter by argument
    {
        $I = $this;
        for ($filterGroup = 1; $filterGroup <= $numberOfFilters; $filterGroup++) { //Start filter cycle
            $I->selectRandomFilter(); //Select random filter
            $I->click("//a[@class='action remove']"); //Click on checkbox with selected filter
            $I->waitForElementNotVisible("//a[@class='action remove']"); //Waiting for checkbox with selected filter to be not visible
            $I->waitPageLoad();
        }
    }

    /**
     * @throws Exception
     */
    public function selectRandomFilter()
    {
        $I = $this;
        $dropdownFiltersCount = $I->getElementsCountByCssSelector("div.filter-options-item"); //Writing variable with dropdown filters count
        $randomDropdownFilter = Factory::create(); //Run Faker create generator
        $randomDropdownFilter = $randomDropdownFilter->numberBetween(1, $dropdownFiltersCount); //Converted to string and generate numberBetween
        $I->click("//div[@class='filter-options-item'][$randomDropdownFilter]"); //Open random filter group dropdown
        $I->wait(1);
        $filtersCount = $I->getElementsCountByCssSelector('div[aria-hidden = "false"] li.item'); //Writing variable with filters count
        $randomFilterNumber = Factory::create(); //Run Faker create generator
        $randomFilterNumber = $randomFilterNumber->numberBetween(1, $filtersCount); //Converted to string and generate numberBetween
        $I->click("div[aria-hidden = false] li.item:nth-of-type($randomFilterNumber) a"); //Click on random filter
        $I->waitForElementVisible("//a[@class='action remove']"); //Waiting for the filter is selected
        $I->waitPageLoad();
    }

}