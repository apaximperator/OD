<?php

use Page\Credentials;

class ProductTester extends AcceptanceTester
{

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
        $P->waitPageLoad(10);
        $productsCount = $P->getElementsCountByCssSelector("li.item.product.product-item");
        $randomProductNumber = rand(1, $productsCount);
        $P->waitForElementClickable("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 10);
        $productLink = $P->grabAttributeFrom("//li[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']", 'href');
        $productLink = str_replace(Credentials::$URL, '', $productLink);
        $P->click("//*[@class='item product product-item'][$randomProductNumber]//a[@class='product-item-link']");
        $P->waitPageLoad();
        $P->seeInCurrentUrl($productLink);
        $P->waitForElementVisible("h1.page-title", 30);
    }

    /**
     * @throws Exception
     */
    public function selectRandomOption()
    {
        $P = $this;
        $P->waitPageLoad();
        $P->waitForElement('select.super-attribute-select', 10);
        $P->seeElement('select.super-attribute-select');
        $selectCount = $P->getElementsCountByCssSelector("select.super-attribute-select"); //Get elements count by selector
        for ($selectByIndex = 1; $selectByIndex <= $selectCount; $selectByIndex++) { //Start cycle for select
            $P->seeElement('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']'); //Check select by index availability
            $optionValueCount = $this->getElementsCountByCssSelector('select.super-attribute-select:nth-child(' . $selectByIndex . ')>option');
            $optionValueNumber = rand(1, $optionValueCount);
            $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            if ($optionValue === "Select Size") {
                $optionValueNumber += $optionValueNumber;
                $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            }
            $P->selectOption('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']', $optionValue); //Select desired option
            $P->see($optionValue, '(//select[contains(@id,"attribute")])[' . $selectByIndex . ']');
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
    public function addProductToCart()
    {
        $P = $this;
        $productCountBefore = $P->grabTextFrom('a.showcart span.counter-number');
        $P->waitForElementClickable('#product-addtocart-button', 10);
        $productTitle = $P->executeJS("return document.querySelector('h1.page-title>span').textContent");
        $P->click("#product-addtocart-button");
        $P->waitForText('ADDED', 10, '#product-addtocart-button span');
        $P->see('ADDED', '#product-addtocart-button span');
        $P->waitForElementNotVisible('.loading-mask', 10);
        $P->waitForElement('a.showcart span.counter-number', 10);
        $productCountAfter = $P->grabTextFrom('a.showcart span.counter-number');
        if ((int)$productCountAfter === (int)$productCountBefore + 1) {
            $P->click('a.showcart');
            $P->waitForElement('.product-item__name a', 10);
            $P->see($productTitle, '.product-item__name a');
            $P->click('#btn-minicart-close');
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
            $optionValueNumber = rand(1, $optionValueCount - 1);
            $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            if ($optionValue === "Select Size") {
                $optionValueNumber += $optionValueNumber;
                $optionValue = $P->grabTextFrom('(//select[contains(@id,"attribute")])[' . $selectByIndex . ']//option[' . $optionValueNumber . ']'); //Writing variable with desired option
            }
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
        $productWishListCountBefore = $P->grabTextFrom('.wishlist a .counter.qty');
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElementClickable('.towishlist');
        $productTitle = $P->grabTextFrom('h1.page-title>span');
        $P->click('.towishlist');
        $P->click('.mfp-close');
        $P->switchToIFrame();
        $productWishListCountAfter = $P->grabTextFrom('.wishlist a .counter.qty');
        if ((int)$productWishListCountAfter === (int)$productWishListCountBefore + 1) {
            $P->click('.wishlist a');
            $P->waitForElement('a.product-item-link');
            $P->see($productTitle, 'a.product-item-link');
            $P->click('#btn-minicart-close');
        } else {
            throw new Exception("Wishlist qty doesn't change");
        }
    }

    /**
     * @throws Exception
     */
    public function addProductToCartOnQuickView()
    {
        $P = $this;
        $productCountBefore = $P->grabTextFrom('a.showcart span.counter-number');
        $P->switchToIFrame('.mfp-iframe');
        $P->waitForElementClickable('#product-addtocart-button', 10);
        $productTitle = $P->executeJS("return document.querySelector('h1.page-title>span').textContent");
        $P->click("#product-addtocart-button");
        $P->waitForText('ADDED', 10, '#product-addtocart-button span');
        $P->see('ADDED', '#product-addtocart-button span');
        $P->switchToIFrame();
        $P->click('.mfp-close');
        $P->waitForElementNotVisible('.loading-mask', 10);
        $P->waitForElement('a.showcart span.counter-number', 10);
        $productCountAfter = $P->grabTextFrom('a.showcart span.counter-number');
        if ((int)$productCountAfter === (int)$productCountBefore + 1) {
            $P->click('a.showcart');
            $P->waitForElement('.product-item__name a', 10);
            $P->see($productTitle, '.product-item__name a');
            $P->click('#btn-minicart-close');
        } else {
            throw new Exception("Cart qty doesn't change");
        }
    }
}