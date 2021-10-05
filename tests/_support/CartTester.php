<?php

class CartTester extends AcceptanceTester
{

    /**
     * @throws Exception
     */
    public function deleteProductFromMiniCart()
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $cartCountBefore = $Cart->grabTextFrom('a.showcart span.counter-number');
        $Cart->click('a.showcart');
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->click('.action.delete');
        $Cart->waitForElement(".action-primary.action-accept");
        $Cart->click(".action-primary.action-accept");
        $Cart->waitForText("YOUR CART IS EMPTY", 10, ".subtitle.empty");
        $Cart->click('#btn-minicart-close');
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ($cartCountBefore - 1 !== (int)$cartCountAfter) {
            throw new Exception("QTY not correct");
        }
    }

    /**
     * @throws Exception
     */
    public function deleteProductFromCart()
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $cartCountBefore = $Cart->grabTextFrom('a.showcart span.counter-number');
        $Cart->click('a.showcart');
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->click("#top-cart-btn-checkout");
        $Cart->waitPageLoad();
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->click('.action.action-delete');
        $Cart->waitForText("Your Cart is Empty", 10, ".page-title");
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ($cartCountBefore - 1 !== (int)$cartCountAfter) {
            throw new Exception("QTY not correct");
        }
    }

    /**
     * @throws Exception
     */
    public function changeProductQtyOnMiniCart()
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $cartCountBefore = $Cart->grabTextFrom('a.showcart span.counter-number');
        $Cart->click('a.showcart');
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->selectOption('select.cart-item-qty', $cartCountBefore + 1);
        $Cart->waitForElement(".icon.icon-update", 10);
        $Cart->click(".icon.icon-update");
        $Cart->see((string)($cartCountBefore + 1), ".select2-selection__rendered");
        $Cart->click('#btn-minicart-close');
        $Cart->wait(2);
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ($cartCountBefore + 1 !== (int)$cartCountAfter) {
            throw new Exception("$cartCountAfter");
        }
    }

    /**
     * @throws Exception
     */
    public function changeProductQtyOnCart()
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $cartCountBefore = $Cart->grabTextFrom('a.showcart span.counter-number');
        $Cart->click('a.showcart');
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->waitForElementClickable('#top-cart-btn-checkout', 10);
        $Cart->click("#top-cart-btn-checkout");
        $Cart->waitPageLoad();
        $Cart->waitForElement('.product-item__name a', 10);
        $Cart->selectOption('select.input-text.qty', $cartCountBefore + 1);
        $Cart->waitAjaxLoad(10);
        $Cart->see($cartCountBefore + 1, ".select2-selection__rendered");
        $Cart->wait(2);
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ($cartCountBefore + 1 !== (int)$cartCountAfter) {
            throw new Exception("$cartCountAfter");
        }
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
    public function removeAllProductsFromCart() //Cycle with 'empty cart' check for remove all products from cart
    {
        $I = $this;
        $I->amOnPage('/checkout/cart/'); //Go to Cart page
        $cartIsNotEmpty = true; //Creating a variable for an empty cart
        while ($cartIsNotEmpty) { //Start cycle for clear cart
            try {
                $I->dontSee('YOUR CART IS EMPTY', "//div[@class='cart-empty']//h1//span"); //Check that there is no 'YOUR CART IS EMPTY' text
                $I->click("(//a[@class='action action-delete'])[1]"); //Remove first product from cart
                $cartIsNotEmpty = true; //Cart is not empty - false
            } catch (Exception $e) {
                $cartIsNotEmpty = false; //Cart is not empty - true
            }
        }
    }

}