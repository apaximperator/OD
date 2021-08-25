<?php

class ProductCest
{

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addAndRemoveProductFromMiniCart(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $productQtyLess2 = true; //Creating a variable for an product with qty less 2
        while ($productQtyLess2) { //Start cycle for check product with qty less 2
            $I->openRandomNotEmptyCategoryWithProductWithAddToCartOrQuickAddButton();
            $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
            $I->selectOptionIfPresent(); //Select desired option if present, if is no select on the page then do nothing
            $I->changeQtyAndAttemptAdding();
            try {
                $I->dontSeeElement("//main[@id='maincontent']//div[contains(text(),'The requested quantity is not available')]"); //Check 'quantity is not available' message
                $productQtyLess2 = false; //This product qty less 2 - false
            } catch (Exception $e) {
                $productQtyLess2 = true; //This product qty less 2 - true
            }
        }
        $I->waitForElementVisible("//button[@id='product-addtocart-button' and @title='Added to Cart']", 30); //Check that 'Add to cart' button is changed title to 'Added to Cart'
        $I->waitForElementVisible("//div[@class='item']//span[@class='counter qty']"); //Waiting for cart counter
        $I->removeAllProductsFromMinicart();
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToCartPopularProduct(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $productQtyLess2 = true; //Creating a variable for an product with qty less 2
        while ($productQtyLess2) { //Start cycle for check product with qty less 2
            $I->openMostPopularCategory(); //Open most popular category
            $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
            $I->selectOptionIfPresent(); //Select desired option if present, if is no select on the page then do nothing
            $I->changeQtyAndAttemptAdding();
            try {
                $I->dontSeeElement("//main[@id='maincontent']//div[contains(text(),'The requested quantity is not available')]"); //Check 'quantity is not available' message
                $productQtyLess2 = false; //This product qty less 2 - false
            } catch (Exception $e) {
                $productQtyLess2 = true; //This product qty less 2 - true
            }
        }
        $I->checkMiniCartIsNotEmpty();
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToCartBrandByAlphabetProduct(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $productQtyLess2 = true; //Creating a variable for an product with qty less 2
        while ($productQtyLess2) { //Start cycle for check product with qty less 2
            $I->openRandomNotEmptyBrandByAlphabetCategory(); //Open random not empty brand category by alphabet
            $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
            $I->selectOptionIfPresent(); //Select desired option if present, if is no select on the page then do nothing
            $I->changeQtyAndAttemptAdding();
            try {
                $I->dontSeeElement("//main[@id='maincontent']//div[contains(text(),'The requested quantity is not available')]"); //Check 'quantity is not available' message
                $productQtyLess2 = false; //This product qty less 2 - false
            } catch (Exception $e) {
                $productQtyLess2 = true; //This product qty less 2 - true
            }
        }
        $I->checkMiniCartIsNotEmpty();
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToCartBrandByImageProduct(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $productQtyLess2 = true; //Creating a variable for an product with qty less 2
        while ($productQtyLess2) { //Start cycle for check product with qty less 2
            $I->openRandomNotEmptyBrandByImageCategory(); //Open random not empty brand category by image
            $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
            $I->selectOptionIfPresent(); //Select desired option if present, if is no select on the page then do nothing
            $I->changeQtyAndAttemptAdding();
            try {
                $I->dontSeeElement("//main[@id='maincontent']//div[contains(text(),'The requested quantity is not available')]"); //Check 'quantity is not available' message
                $productQtyLess2 = false; //This product qty less 2 - false
            } catch (Exception $e) {
                $productQtyLess2 = true; //This product qty less 2 - true
            }
        }
        $I->checkMiniCartIsNotEmpty();
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToCartSelectYourItemsProduct(CategoryTester $I) //stage https://nzm-retail.staging.overdose.digital/shop-by-category/supplement-stacks
    {
        $I->amOnPage('/'); //Open Homepage
        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
        $I->openSupplementStacksCategory();
        $I->openRandomProductWithSelectYourItemsButton();
        $I->selectOptionIfPresentForBundle();
        $I->addProductToCart();
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToWishlist(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        try {
            $I->dontSeeElement("//div[contains(@class,'login')]//a[contains(@class,'login')]"); //If I'm logged in then don't need to login
        } catch (Exception $e) {
            $I->login();
        }
        $I->openRandomNotEmptyCategoryWithProductWithAddToCartOrQuickAddButton();
        $I->openRandomProductWithAddToCartOrQuickAddButton(); //Open random product with 'Add to cart' or 'Quick add' button
        $I->addAndRemoveProductToWishlist(); //Add and remove product to wishlist
    }

    /**
     * @param CategoryTester $I
     * @throws Exception
     */
    public function addToCompare(CategoryTester $I)
    {
        $I->amOnPage('/'); //Open Homepage
        $I->openRandomNotEmptyCategory();
        $I->openRandomProduct();
        $I->addProductToCompare();
    }

}
