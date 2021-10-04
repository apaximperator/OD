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
        $Cart->click('#btn-minicart-close');
        $Cart->reloadPage();
        $Cart->waitPageLoad();
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ($cartCountBefore + 1 !== (int)$cartCountAfter) {
            throw new Exception("$cartCountAfter");
        }
    }

    /**
     * @throws Exception
     */
    public function changeProductQtyOnCartPage()
    {
        $I = $this;
        $I->waitForElementClickable("//tr[@class='item-info ']//a[contains(@class,'down')]"); //Waiting for 'down qty' is clickable
        $I->click("//tr[@class='item-info ']//a[contains(@class,'down')]"); //Click on 'down qty' button
        $I->clickWithLeftButton("//h1[@class='page-title']"); //Click to nowhere to change qty
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitPageLoad();
        $I->seeElement("//tr[@class='item-info ']//input[@value='1']"); //Check that qty is changed
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