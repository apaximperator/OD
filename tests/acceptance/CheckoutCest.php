<?php

class CheckoutCest
{
    /**
     * @param GlobalTester $G
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function checkOutWithLogin(GlobalTester $G, CartTester $Cart, CategoryTester $C, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->goToCheckout();
        $Ch->loginFromCheckOut();
        $Ch->waitForElementVisible('div.step-title', 10);
        $Ch->randomDeliveryMethod();
        $Ch->paymentMethodByArgument("laybuy_payment", 'Laybuy');
    }

    /**
     * @param GlobalTester $G
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function checkOutForGuest(GlobalTester $G, CartTester $Cart, CategoryTester $C, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->goToCheckout();
        $Ch->userGuestData();
        $Ch->randomDeliveryMethod();
        $Ch->paymentMethodByArgument("laybuy_payment", 'Laybuy');
    }

    /**
     * @param GlobalTester $G
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function checkOutForAlreadyLoggedUser(GlobalTester $G, CartTester $Cart, CategoryTester $C, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $G->login();
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->goToCheckout();
        $Ch->waitForElementVisible('div.step-title', 10);
        $Ch->randomDeliveryMethod();
        $Ch->paymentMethodByArgument("laybuy_payment", 'Laybuy');
    }

    /**
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param GlobalTester $G
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function creditCard(CartTester $Cart, CategoryTester $C, GlobalTester $G, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $G->login();
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->processCheckoutForLoggedUser('paymentexpress_pxpay2', 'Windcave Payment Page');
    }

    /**
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param GlobalTester $G
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function layBuy(CartTester $Cart, CategoryTester $C, GlobalTester $G, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $G->login();
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->processCheckoutForLoggedUser("laybuy_payment", 'Laybuy');
    }

    /**
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param GlobalTester $G
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function paypal(CartTester $Cart, CategoryTester $C, GlobalTester $G, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $G->login();
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->processCheckoutForLoggedUser("paypal_express", 'Log in to your PayPal account');
    }

    /**
     * @param CartTester $Cart
     * @param CategoryTester $C
     * @param GlobalTester $G
     * @param ProductTester $P
     * @param CheckoutTester $Ch
     * @throws Exception
     */
    public function afterpay(CartTester $Cart, CategoryTester $C, GlobalTester $G, ProductTester $P, CheckoutTester $Ch)
    {
        $G->amOnPage('/');
        $G->login();
        $Cart->removeAllProductsFromMinicart();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Ch->processCheckoutForLoggedUser("afterpaypayovertime", "Afterpay");
    }

}
