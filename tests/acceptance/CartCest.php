<?php

class CartCest
{

    /**
     * @param CategoryTester $C
     * @param ProductTester $P
     * @param CartTester $Cart
     * @throws Exception
     */
    public function miniCart(CategoryTester $C, ProductTester $P, CartTester $Cart)
    {
        $C->amOnPage('/');
        $C->closePopup();
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Cart->changeProductQtyOnMiniCart();
        $Cart->deleteProductFromMiniCart();
    }


    /**
     * @param CategoryTester $C
     * @param ProductTester $P
     * @param CartTester $Cart
     * @throws Exception
     */
    public function cart(CategoryTester $C, ProductTester $P, CartTester $Cart)
    {
        $C->amOnPage('/');
        $C->openRandomNotEmptyPLP();
        $P->openRandomProduct();
        $P->selectRandomOption();
        $P->addProductToCart();
        $Cart->changeProductQtyOnCart();
        $Cart->deleteProductFromCart();
    }

}
