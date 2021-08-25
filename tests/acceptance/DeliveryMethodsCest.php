<?php

class DeliveryMethodsCest
{

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function clickAndCollectTest(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->openRandomNotEmptyCategoryWithProductWithAddToCartOrQuickAddButton();
        $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with active "Add to cart" button
        $I->selectOptionIfPresent(); //Select options on product page
        $I->addProductToCartAndGoToCheckout(); //Add product to cart and go to Checkout page
        $I->wait(5);
        $I->waitPageLoad();
        $I->clickAndCollect(); //Choose "Click and Collect" delivery method
        $I->userGuestData();
        $I->click("//button[contains(@class,'continue')]"); //Click to "Continue" button
        $I->waitPageLoad();
        $I->paymentCreditCardOrAfterPay(); //Pay via Credit card or AfterPay
    }

    /*
        /**
         * @param CheckoutTester $I
         * @throws Exception
         */
    /*public function BulkyProductsTest(CheckoutTester $I) THIS TEST WORK ONLY ON PRODUCTION
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->bulkySearch(); //Search bulky product
        $I->wait("5");
        $I->click("//div[@class='minicart-wrapper']"); //click to mini cart
        $I->wait("5");
        $I->click("//button[contains(@class,'checkout')]"); //click to "Checkout" button
        $I->waitPageLoad();
        $I->userGuestData(); //Add user data on checkout page
        $I->bulkyDeliveryMethod(); //Choose a Delivery method for bulky products
        $I->paymentCreditCardorAfterPay(); //Pay via Credit card or AfterPay


    }*/

}