<?php

use Faker\Factory;
use Page\Credentials;

class ProductTester extends CartTester
{

    /**
     * @throws Exception
     */
    public function selectOptionIfPresent()
    {
        $P = $this;
        $selectCount = $P->getElementsCountByCssSelector("select[id][name]"); //Get elements count by selector
        for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
            try {
                $P->seeElement("(//select[contains(@id,'attribute')])[$selectByIndex]"); //Check select by index availability
                $optionValue = $P->grabTextFrom("(//select[contains(@id,'attribute')])[$selectByIndex]//option[2]"); //Writing variable with desired option
                $P->selectOption("(//select[contains(@id,'attribute')])[$selectByIndex]", $optionValue); //Select desired option
            } catch (Exception $e) {
                //If is not select by index on the page then do nothing
            }
        }
    }

    /**
     * @throws Exception
     */
    public function selectOptionIfPresentForBundle() //Select desired option if present, if is no select on the page then do nothing
    {
        $P = $this;
        try {
            $P->seeElement("(//select[@id and @name])[1]"); //Check select availability
            $P->wait(5);
            $selectCount = $P->getElementsCountByCssSelector("select[id][name]"); //Get elements count by selector
            for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
                $optionsCount = $P->getElementsCountByCssSelector("select[id][name]"); //Writing variable with options count
                $optionNumber = Factory::create(); //Run Faker create generator
                $optionNumber = $optionNumber->numberBetween(2, $optionsCount); //Converted to string and generate numberBetween
                $optionValue = $P->grabTextFrom("(//select[contains(@id,'bundle-option-')])[$selectByIndex]//option[$optionNumber]"); //Writing variable with desired option
                $P->wait(3);
                $P->selectOption("(//select[contains(@id,'bundle-option-')])[$selectByIndex]", $optionValue); //Select desired option
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
        $P = $this;
        $P->addProductToCart(); //Add product to cart
        $P->wait(10);
        $P->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $P->wait(3);
        $P->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]", 5); //Waiting for opening Minicart
        if ($P->tryToSeeElement("//div[@class='ampromo_element']//div[@class='ampromo-promotion-message']")) { // Check that promo message displays on mini cart
            $P->click("//div[@class='secondary']//a[@class='action viewcart']"); // Click to "View cart" link
            $P->waitPageLoad();
            $P->see('My Cart'); // Check that Cart page opens
            $P->click("//div[@class='cart-summary-methods']//button[@class='action primary checkout']"); // Click to 'Proceed to checkout' button
        } else {
            $P->waitForElementClickable("//button[@id='top-cart-btn-checkout']"); //Waiting for 'Proceed to checkout' button is clickable
            $P->wait(1); //For minicart full loading
            $P->click("//button[@id='top-cart-btn-checkout']"); //Click on 'Proceed to checkout' button
            $P->waitForElementVisible("//div[@class='loader']", 30); //Waiting for preloader to appear
            $P->waitForElementNotVisible("//div[@class='loader']", 30); //Waiting for preloader to disappear
            //Waiting for full page load
        }
        $P->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function changeQtyAndAttemptAdding()
    {
        $P = $this;
        $P->fillField("//input[@id='qty']", '2'); //Change qty
        $P->waitPageLoad();
        $P->waitForElementClickable("//button[@id='product-addtocart-button']", 15); //Waiting for 'Add to cart' button is clickable
        $P->click("//button[@id='product-addtocart-button']"); //Click on 'Add to cart' button
        $P->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $P->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $P->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function checkMiniCartIsNotEmpty()
    {
        $P = $this;
        $P->waitForElementVisible("//button[@id='product-addtocart-button' and @title='Added to Cart']", 30); //Check that 'Add to cart' button is changed title to 'Added to Cart'
        $P->waitForElementVisible("//div[@class='item']//span[@class='counter qty']"); //Waiting for cart counter
        $P->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $P->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]"); //Waiting for opening Minicart
    }

    /**
     * @throws Exception
     */
    public function addProductToCart()
    {
        $P = $this;
        $P->waitForElementClickable("//button[@id='product-addtocart-button']", 30); //Waiting for 'Add to cart' button is clickable
        $P->click("//button[@id='product-addtocart-button']"); //Click on 'Add to cart' button
        $P->waitForElementVisible("//div[@class='item']//span[@class='counter qty']", 30); //Waiting for cart counter
        $P->waitForElementVisible("//button[@id='product-addtocart-button' and @title='Added to Cart']", 30); //Check that 'Add to cart' button is changed title to 'Added to Cart'
    }

    /**
     * @throws Exception
     */
    public function removeAllProductsFromMinicart()  //Cycle with 'empty cart' check for remove all products from minicart
    {
        $P = $this;
        $P->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $P->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]"); //Waiting for opening Minicart
        $cartIsNotEmpty = true; //Creating a variable for an empty cart
        while ($cartIsNotEmpty) { //Start cycle for clear cart
            try {
                $P->dontSee('YOUR CART IS EMPTY', "//strong[@class='subtitle empty']"); //Check that there is no 'YOUR CART IS EMPTY' text
                $P->waitForElementClickable("(//a[@class='action delete'])[last()]"); //Waiting for remove last product button is clickable
                $P->click("(//a[@class='action delete'])[last()]"); //Remove last product button
                $P->waitForElementClickable("//button[@class='action-primary action-accept']"); //Waiting remove confirmation popup
                $P->click("//button[@class='action-primary action-accept']"); //CLick on 'OK' button
                $P->wait(1);
                $P->waitAjaxLoad();
                $cartIsNotEmpty = true; //Cart is not empty - false
            } catch (Exception $e) {
                $cartIsNotEmpty = false; //Cart is not empty - true
            }
        }
        $P->click("//a[@id='btn-minicart-close']"); //Click to 'Continue shopping' button
        $P->waitForElementNotVisible("//div[@class='item']//span[@class='counter qty']"); //Check than cart counter is not visible
    }

    /**
     * @throws Exception
     */
    public function addAndRemoveProductToWishlist()
    {
        $P = $this;
        $P->waitForElementClickable("//a[@class='action towishlist']", 30); //Waiting for 'Add to wishlist' button
        $P->click("//a[@class='action towishlist']"); //Click on 'Add to wishlist' button
        $P->waitPageLoad();
        $P->waitForElementVisible("//ul[@class='wishlist-items']//li[contains(@class,'wishlist')]"); //Waiting for wishlist list
        $P->waitForElementClickable("//a[@class='btn-remove']"); //Wait for 'remove' button is clickable
        $P->click("//a[@class='btn-remove']"); //Click on 'remove' button
        $P->waitPageLoad();
        $P->waitForText('You have no items in your wish list.', 15, "//div[@class='message info empty']//span"); //Waiting for 'empty' message
    }

    /**
     * @throws Exception
     */
    public function openQuickViewForRandomProduct()
    {
        $P = $this;
        $productsCount = $P->getElementsCountByCssSelector('a.product span.product-image-container:first-child span div.bss-bt-quickview a');
        $randomProductNumber = rand(0, $productsCount - 1);
        $P->executeJS('document.querySelectorAll("a.product span.product-image-container:first-child span div.bss-bt-quickview a")[' . $randomProductNumber . '].click()');
        $P->waitForElementNotVisible('.mfp-preloader', 10);
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElementClickable('#product-addtocart-button', 10);
        $P->seeElement('#product-addtocart-button');
        $P->switchToIFrame();
    }

    /**
     * @throws Exception
     */
    public function openRandomProduct()
    {
        $P = $this;
        $productsCount = $P->getElementsCountByCssSelector('a.product span.product-image-container:first-child span div.bss-bt-quickview a');
        $randomProductNumber = rand(0, $productsCount - 1);
        $ProductLink = $P->executeJS('return document.querySelectorAll("a.product-item-link")[' . $randomProductNumber . '].getAttribute("href");');
        $ProductLink = str_replace(Credentials::$URL, '', $ProductLink);
        $P->executeJS('document.querySelectorAll("a.product-item-link")[' . $randomProductNumber . '].click()');
        $P->waitPageLoad();
        $P->canSeeInCurrentUrl($ProductLink);
    }

    /**
     * @throws Exception
     */
    public function selectRandomOption()
    {
        $P = $this;
        $P->waitForElement('select.super-attribute-select', 10);
        $P->seeElement('select.super-attribute-select');
        $selectCount = $P->getElementsCountByCssSelector("select.super-attribute-select"); //Get elements count by selector
        for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
            $P->seeElement('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']'); //Check select by index availability
            $optionValueCount = $this->getElementsCountByCssSelector('select.super-attribute-select:nth-child(' . $selectByIndex . ')>option');
            $optionValueNumber = rand(1, $optionValueCount);
            $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            $P->selectOption('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']', $optionValue); //Select desired option
            $P->see($optionValue, '(//select[contains(@id,"attribute")])[' . $selectByIndex . ']');
            $P->wait(.5);
        }
    }

    /**
     * @throws Exception
     */
    public function selectRandomQTY()
    {
        $P = $this;
        $P->waitForElement('select#qty', 10);
        $P->seeElement('select#qty');
        $QTYValueCount = $this->getElementsCountByCssSelector('select#qty>option');
        $QTYValueNumber = rand(1, $QTYValueCount);
        $QTYValue = $P->grabTextFrom('(//select[contains(@id,"qty")])//option[' . $QTYValueNumber . ']'); //Writing variable with desired option
        $P->selectOption('(//select[contains(@id,"qty")])', $QTYValue); //Select desired option
        $P->wait(1);
        $P->see($QTYValue, '(//select[contains(@id,"qty")])');
    }


    /**
     * @throws Exception
     */
    public function addRandomProductToCart()
    {
        $P = $this;
        $cartCountBefore = $P->grabTextFrom('a.showcart span.counter-number');
        $P->waitForElementClickable('#product-addtocart-button', 10);
        $productTitle = $P->grabTextFrom('h1.page-title>span');
        $P->click("#product-addtocart-button");
        $P->waitForText('ADDED', 10, '#product-addtocart-button span');
        $P->see('ADDED', '#product-addtocart-button span');
        $P->waitForElementNotVisible('.loading-mask', 10);
        $P->waitForElement('a.showcart span.counter-number', 10);
        $cartCountAfter = $P->grabTextFrom('a.showcart span.counter-number');
        if ((int)$cartCountAfter === (int)$cartCountBefore + 1) {
            $P->click('a.showcart');
            $P->waitForElement('.product-item__name a', 10);
            $P->see($productTitle, '.product-item__name a');
            $P->wait(5);
        } else {
            throw new Exception("Cart qty doesn't change");
        }
    }

    /**
     * @throws Exception
     */
    public function selectRandomOptionOnQuickView()
    {
        $P = $this;
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElement('select.super-attribute-select', 10);
        $P->seeElement('select.super-attribute-select');
        $selectCount = $P->getElementsCountByCssSelector("select.super-attribute-select"); //Get elements count by selector
        for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
            $P->seeElement('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']'); //Check select by index availability
            $optionValueCount = $this->getElementsCountByCssSelector('select.super-attribute-select:nth-child(' . $selectByIndex . ')>option');
            $optionValueNumber = rand(1, $optionValueCount);
            $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            $P->selectOption('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']', $optionValue); //Select desired option
            $P->see($optionValue, '(//select[contains(@id,"attribute")])[' . $selectByIndex . ']');
        }
        $P->switchToIFrame();
    }

    /**
     * @throws Exception
     */
    public function selectRandomQTYOnQuickView()
    {
        $P = $this;
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElement('select#qty', 10);
        $P->seeElement('select#qty');
        $QTYValueCount = $this->getElementsCountByCssSelector('select#qty>option');
        $QTYValueNumber = rand(1, $QTYValueCount);
        $QTYValue = $P->grabTextFrom('(//select[contains(@id,"qty")])//option[' . $QTYValueNumber . ']'); //Writing variable with desired option
        $P->selectOption('(//select[contains(@id,"qty")])', $QTYValue); //Select desired option
        $P->wait(1);
        $P->see($QTYValue, '(//select[contains(@id,"qty")])');
        $P->switchToIFrame();
    }

    /**
     * @throws Exception
     */
    public function addRandomProductToWishListOnQuickView() //TODO Дописать когда вишлист будет работать корректно.
    {
        $P = $this;
        $wishListCountBefore = $P->grabTextFrom('.wishlist a .counter.qty');
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElementClickable('.towishlist');
        $productTitle = $P->grabTextFrom('h1.page-title>span');
        $P->click('.towishlist');
        $P->click('.mfp-close');
        $P->switchToIFrame();
        $wishListCountAfter = $P->grabTextFrom('.wishlist a .counter.qty');
        if ((int)$wishListCountAfter === (int)$wishListCountBefore + 1) {
            $P->click('.wishlist a');
            $P->waitForElement('a.product-item-link');
            $P->see($productTitle, 'a.product-item-link');
        } else {
            throw new Exception("Wishlist qty doesn't change");
        }
    }

    /**
     * @throws Exception
     */
    public function addRandomProductToCartOnQuickView()
    {
        $P = $this;
        $cartCountBefore = $P->grabTextFrom('a.showcart span.counter-number');
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElementClickable('#product-addtocart-button', 10);
        $productTitle = $P->grabTextFrom('h1.page-title>span');
        $P->click("#product-addtocart-button");
        $P->waitForText('ADDED', 10, '#product-addtocart-button span');
        $P->see('ADDED', '#product-addtocart-button span');
        $P->switchToIFrame();
        $P->click('.mfp-close');
        $P->waitForElementNotVisible('.loading-mask', 10);
        $P->waitForElement('a.showcart span.counter-number', 10);
        $cartCountAfter = $P->grabTextFrom('a.showcart span.counter-number');
        if ((int)$cartCountAfter === (int)$cartCountBefore + 1) {
            $P->click('a.showcart');
            $P->waitForElement('.product-item__name a', 10);
            $P->see($productTitle, '.product-item__name a');
            $P->wait(5);
        } else {
            throw new Exception("Cart qty doesn't change");
        }
    }
}