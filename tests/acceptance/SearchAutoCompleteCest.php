<?php

class SearchAutoCompleteCest
{

    /**
     * @param ProductTester $I
     * @throws Exception
     */
    public function searchAutoCompleteGuestTest(ProductTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->searchAutoComplete(); //Search and open product via Autocomplete
        $I->wait(5);
        $I->selectOptionIfPresent(); //Select options on product page
        $I->addProductToCartAndGoToCheckout(); //Add product to cart and go to Checkout page
    }

    /**
     * @param ProductTester $I
     * @throws Exception
     */
    public function searchAutoCompleteCustomerTest(ProductTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->login(); //Login as customer
        $I->removeAllProductsFromCart(); //Remove product from cart
        $I->searchAutoComplete(); //Search and open product via Autocomplete
        $I->wait(5);
        $I->selectOptionIfPresent(); //Select options on product page
        $I->addProductToCartAndGoToCheckout(); //Add product to cart and go to Checkout page
        $I->randomDeliveryMethod(); //Choose random delivery method
        $I->paymentCreditCardOrAfterPay(); //Pay via Credit card or AfterPay
    }

}
