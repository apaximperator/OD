<?php
//
//class CheckoutCest
//{
//
//    /**
//     * @param CartTester $I
//     * @param GlobalTester $G
//     * @param CheckoutTester $C
//     * @throws Exception
//     */
//    public function afterpay(CartTester $I, GlobalTester $G, CheckoutTester $C)
//    {
//        $I->amOnPage('/');
//        $G->login();
//        $I->removeAllProductsFromCart();
//        $G->addProductToCartFromCategory(1, '/shop-by-category/supplement-stacks');
//        $C->processCheckoutForLoggedUser('AFTERPAY');
//    }
//
//    /**
//     * @param CartTester $I
//     * @param GlobalTester $G
//     * @param CheckoutTester $C
//     * @throws Exception
//     */
//    public function creditCard(CartTester $I, GlobalTester $G, CheckoutTester $C)
//    {
//        $I->amOnPage('/');
//        $G->login();
//        $I->removeAllProductsFromCart();
//        $G->addProductToCartFromCategory(1, '/shop-by-category/supplement-stacks');
//        $C->processCheckoutForLoggedUser('Credit Card');
//    }
//
//    /**
//     * @param CartTester $I
//     * @param GlobalTester $G
//     * @param CheckoutTester $C
//     * @throws Exception
//     */
//    public function layBuy(CartTester $I, GlobalTester $G, CheckoutTester $C)
//    {
//        $I->amOnPage('/');
//        $G->login();
//        $I->removeAllProductsFromCart();
//        $G->addProductToCartFromCategory(1, '/shop-by-category/supplement-stacks');
//        $C->processCheckoutForLoggedUser('LAYBUY');
//    }
//
//    /**
//     * @param CategoryTester $I
//     * @throws Exception
//     */
////    public function dps(CategoryTester $I)
////    {
////        $I->amOnPage('/'); //Open Homepage
////        try {
////            $I->dontSeeElement("//div[contains(@class,'login')]//a[contains(@class,'login')]"); //If I'm logged in then don't need to login
////        } catch (Exception $e) {
////            $I->login();
////        }
////        $I->openRandomNotEmptyCategoryWithProductWithAddToCartOrQuickAddButton();
////        $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
////        $I->selectOptionIfPresent(); //Select desired option if present, if is no select on the page then do nothing
////        $I->addProductToCartAndGoToCheckout(); //Add product to cart and go to checkout page
////        $I->randomDeliveryMethod();//Select random delivery method and go to payment page
////        $I->paymentMethodByArgument("//input[@id='overdose_odpxpay2']/parent::div/label", "//div[@id='PxPayAccount2AccountAuth_Logo']", "/pxmi3/"); //Select 'DPS' payment method and check logo and URL on DPS method page
////        $I->removeAllProductsFromCart(); //Remove products from cart until you get 'YOUR CART IS EMPTY' text
////    }
//
//    /**
//     * @param CartTester $I
//     * @param GlobalTester $G
//     * @param CheckoutTester $C
//     * @throws Exception
//     */
//    public function humm(CartTester $I, GlobalTester $G, CheckoutTester $C)
//    {
//        $I->amOnPage('/');
//        $G->login();
//        $I->removeAllProductsFromCart();
//        $G->addProductToCartFromCategory(1, '/shop-by-category/supplement-stacks');
//        $C->processCheckoutForLoggedUser('Humm');
//    }
//
////    /**
////     * @param CategoryTester $I
////     * @throws Exception
////     */
////    public function clearCart(CategoryTester $I)
////    {
////        $I->amOnPage('/'); //Open Homepage
////        try {
////            $I->dontSeeElement("//div[contains(@class,'login')]//a[contains(@class,'login')]"); //If I'm logged in then don't need to login
////        } catch (Exception $e) {
////            $I->login();
////        }
////        $I->removeAllProductsFromCart(); //Remove products from cart until you get 'YOUR CART IS EMPTY' text
////    }
//
//}
