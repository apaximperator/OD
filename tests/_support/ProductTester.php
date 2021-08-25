<?php

use Faker\Factory;

class ProductTester extends CartTester
{

    /**
     * @throws Exception
     */
    public function selectOptionIfPresent() //Select desired option if present, if is no select on the page then do nothing
    {
        $I = $this;
        $selectCount = $I->getElementsCountByCssSelector("select[id][name]"); //Get elements count by selector
        for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
            try {
                $I->seeElement("(//select[contains(@id,'attribute')])[$selectByIndex]"); //Check select by index availability
                $optionValue = $I->grabTextFrom("(//select[contains(@id,'attribute')])[$selectByIndex]//option[2]"); //Writing variable with desired option
                $I->selectOption("(//select[contains(@id,'attribute')])[$selectByIndex]", $optionValue); //Select desired option
            } catch (Exception $e) {
                //If is no select by index on the page then do nothing
            }
        }
    }

    /**
     * @throws Exception
     */
    public function selectOptionIfPresentForBundle() //Select desired option if present, if is no select on the page then do nothing
    {
        $I = $this;
        try {
            $I->seeElement("(//select[@id and @name])[1]"); //Check select availability
            $I->wait(5);
            $selectCount = $I->getElementsCountByCssSelector("select[id][name]"); //Get elements count by selector
            for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
                $optionsCount = $I->getElementsCountByCssSelector("select[id][name]"); //Writing variable with options count
                $optionNumber = Factory::create(); //Run Faker create generator
                $optionNumber = $optionNumber->numberBetween(2, $optionsCount); //Converted to string and generate numberBetween
                $optionValue = $I->grabTextFrom("(//select[contains(@id,'bundle-option-')])[$selectByIndex]//option[$optionNumber]"); //Writing variable with desired option
                $I->wait(3);
                $I->selectOption("(//select[contains(@id,'bundle-option-')])[$selectByIndex]", $optionValue); //Select desired option
            }
        } catch (Exception $e) {
            //If is no select on the page then do nothing
        }
    }

    /**
     * @throws Exception
     */
    public function addProductToCartAndGoToCheckout()
    {
        $I = $this;
        $I->addProductToCart(); //Add product to cart
        $I->wait(10);
        $I->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $I->wait(3);
        $I->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]", 5); //Waiting for opening Minicart
        if ($I->tryToSeeElement("//div[@class='ampromo_element']//div[@class='ampromo-promotion-message']")) { // Check that promo message displays on mini cart
            $I->click("//div[@class='secondary']//a[@class='action viewcart']"); // Click to "View cart" link
            $I->waitPageLoad();
            $I->see('My Cart'); // Check that Cart page opens
            $I->click("//div[@class='cart-summary-methods']//button[@class='action primary checkout']"); // Click to 'Proceed to checkout' button
            $I->waitPageLoad();
        } else {
            $I->waitForElementClickable("//button[@id='top-cart-btn-checkout']"); //Waiting for 'Proceed to checkout' button is clickable
            $I->wait(1); //For minicart full loading
            $I->click("//button[@id='top-cart-btn-checkout']"); //Click on 'Proceed to checkout' button
            $I->waitForElementVisible("//div[@class='loader']", 30); //Waiting for preloader to appear
            $I->waitForElementNotVisible("//div[@class='loader']", 30); //Waiting for preloader to disappear
            $I->waitPageLoad(); //Waiting for full page load
        }
    }

    /**
     * @throws Exception
     */
    public function changeQtyAndAttemptAdding()
    {
        $I = $this;
        $I->fillField("//input[@id='qty']", '2'); //Change qty
        $I->waitPageLoad();
        $I->waitForElementClickable("//button[@id='product-addtocart-button']", 15); //Waiting for 'Add to cart' button is clickable
        $I->click("//button[@id='product-addtocart-button']"); //Click on 'Add to cart' button
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function checkMiniCartIsNotEmpty()
    {
        $I = $this;
        $I->waitForElementVisible("//button[@id='product-addtocart-button' and @title='Added to Cart']", 30); //Check that 'Add to cart' button is changed title to 'Added to Cart'
        $I->waitForElementVisible("//div[@class='item']//span[@class='counter qty']"); //Waiting for cart counter
        $I->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $I->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]"); //Waiting for opening Minicart
    }

    /**
     * @throws Exception
     */
    public function addProductToCart()
    {
        $I = $this;
        $I->waitForElementClickable("//button[@id='product-addtocart-button']", 30); //Waiting for 'Add to cart' button is clickable
        $I->click("//button[@id='product-addtocart-button']"); //Click on 'Add to cart' button
        $I->waitForElementVisible("//div[@class='item']//span[@class='counter qty']", 30); //Waiting for cart counter
        $I->waitForElementVisible("//button[@id='product-addtocart-button' and @title='Added to Cart']", 30); //Check that 'Add to cart' button is changed title to 'Added to Cart'
    }

    /**
     * @throws Exception
     */
    public function removeAllProductsFromMinicart()  //Cycle with 'empty cart' check for remove all products from minicart
    {
        $I = $this;
        $I->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $I->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]"); //Waiting for opening Minicart
        $cartIsNotEmpty = true; //Creating a variable for an empty cart
        while ($cartIsNotEmpty) { //Start cycle for clear cart
            try {
                $I->dontSee('YOUR CART IS EMPTY', "//strong[@class='subtitle empty']"); //Check that there is no 'YOUR CART IS EMPTY' text
                $I->waitForElementClickable("(//a[@class='action delete'])[last()]"); //Waiting for remove last product button is clickable
                $I->click("(//a[@class='action delete'])[last()]"); //Remove last product button
                $I->waitForElementClickable("//button[@class='action-primary action-accept']"); //Waiting remove confirmation popup
                $I->click("//button[@class='action-primary action-accept']"); //CLick on 'OK' button
                $I->wait(1);
                $I->waitAjaxLoad();
                $cartIsNotEmpty = true; //Cart is not empty - false
            } catch (Exception $e) {
                $cartIsNotEmpty = false; //Cart is not empty - true
            }
        }
        $I->click("//a[@id='btn-minicart-close']"); //Click to 'Continue shopping' button
        $I->waitForElementNotVisible("//div[@class='item']//span[@class='counter qty']"); //Check than cart counter is not visible
    }

    /**
     * @throws Exception
     */
    public function addAndRemoveProductToWishlist()
    {
        $I = $this;
        $I->waitForElementClickable("//a[@class='action towishlist']", 30); //Waiting for 'Add to wishlist' button
        $I->click("//a[@class='action towishlist']"); //Click on 'Add to wishlist' button
        $I->waitPageLoad();
        $I->waitForElementVisible("//ul[@class='wishlist-items']//li[contains(@class,'wishlist')]"); //Waiting for wishlist list
        $I->waitForElementClickable("//a[@class='btn-remove']"); //Wait for 'remove' button is clickable
        $I->click("//a[@class='btn-remove']"); //Click on 'remove' button
        $I->waitPageLoad();
        $I->waitForText('You have no items in your wish list.', 15, "//div[@class='message info empty']//span"); //Waiting for 'empty' message
    }

    /**
     * @throws Exception
     */
    public function addProductToCompare()
    {
        $I = $this;
        $I->waitForElementClickable("//a[@class='action tocompare']", 30); //Waiting for 'Add to wishlist' button
        $I->click("//a[@class='action tocompare']"); //Click on 'Add to wishlist' button
        $I->waitPageLoad();
        $I->waitForElementVisible("//div[@class='compare-slider-block']"); //Waiting for compare block is open
        $I->waitForElementClickable("//a[@class='action primary compare-all']"); //Waiting for 'Compare selected' button is clickable
        $I->wait(1);
        $I->click("//a[@class='action primary compare-all']"); //Click on 'Compare selected' button
        $I->waitPageLoad();
        $I->waitForElementVisible("//h1//span[contains(text(),'Compare Products')]", 30); //Check 'Compare products' text
        $I->seeInCurrentUrl('/catalog/product_compare/'); //Check in current URL
        $I->seeElement("//table[@id='product-comparison']"); //Check product compare table
    }

}