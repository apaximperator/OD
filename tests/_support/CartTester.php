<?php

class CartTester extends AcceptanceTester
{

    /**
     * @throws Exception
     */
    public function openCartPage()
    {
        $I = $this;
        $I->click("//div[@class='item']//a[@class='action showcart']"); //Click on 'Minicart'
        $I->waitForElementVisible("//div[@id='minicart-content-wrapper']//span[contains(text(),'My cart')]"); //Waiting for opening Minicart
        $I->waitForElementClickable("//a[@class='action viewcart']"); //Waiting for 'VIEW AND EDIT CART' is clickable
        $I->click("//a[@class='action viewcart']"); //Click on 'VIEW AND EDIT CART' button
        $I->waitPageLoad();
        $I->seeCurrentUrlEquals('/checkout/cart/'); //Check 'Cart' page URL
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