<?php

class CartTester extends GlobalTester
{

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
        $cartProductQTY = $Cart->grabTextFrom('.select2-selection__rendered');
        $Cart->selectOption('select.cart-item-qty', (int)$cartProductQTY + 1);
        $Cart->waitForElement(".icon.icon-update", 10);
        $Cart->click(".icon.icon-update");
        $Cart->see(((int)$cartProductQTY + 1), ".select2-selection__rendered");
        $Cart->click('#btn-minicart-close');
        $Cart->wait(3);
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ((int)($cartCountBefore + 1) !== (int)$cartCountAfter) {
            throw new Exception("QTY doesn't change");
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
        $Cart->waitForElement('.product-item-name', 10);
        $cartProductQTY = $Cart->executeJS('return document.querySelectorAll(".select2-selection__rendered")[0].textContent');
        $Cart->selectOption('select.input-text.qty', ((int)$cartProductQTY + 1));
        $Cart->waitAjaxLoad(10);
        $Cart->see(((int)$cartProductQTY + 1), ".select2-selection__rendered");
        $Cart->wait(3);
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ((int)($cartCountBefore + 1) !== (int)$cartCountAfter) {
            throw new Exception("QTY doesn't change");
        }
    }

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
        $cartProductCount = $Cart->grabTextFrom('.select2-selection__rendered');
        $Cart->click('.action.delete');
        $Cart->waitForElementClickable(".action-primary.action-accept", 10);
        $Cart->click(".action-primary.action-accept");
        $Cart->waitForText("YOUR CART IS EMPTY", 10, ".subtitle.empty");
        $Cart->click('#btn-minicart-close');
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ((int)$cartCountBefore - (int)$cartProductCount !== (int)$cartCountAfter) {
            throw new Exception("QTY not correct");
        }
    }

    /**
     * @throws Exception
     */
    public function removeAllProductsFromMinicart()  //Cycle with 'empty cart' check for remove all products from minicart
    {
        $Cart = $this;
        $Cart->click('a.showcart');
        $cartIsNotEmpty = true; //Creating a variable for an empty cart
        while ($cartIsNotEmpty) { //Start cycle for clear cart
            try {
                $Cart->dontSee('YOUR CART IS EMPTY', ".subtitle.empty"); //Check that there is no 'YOUR CART IS EMPTY' text
                $Cart->waitForElementClickable("(//a[@class='action delete'])[last()]"); //Waiting for remove last product button is clickable
                $Cart->click("(//a[@class='action delete'])[last()]"); //Remove last product button
                $Cart->waitForElement(".action-primary.action-accept", 10);
                $Cart->click(".action-primary.action-accept");
                $Cart->waitAjaxLoad();
                $cartIsNotEmpty = true; //Cart is not empty - false
            } catch (Exception $e) {
                $cartIsNotEmpty = false; //Cart is not empty - true
            }
        }
        $Cart->see('YOUR CART IS EMPTY', ".subtitle.empty"); //Check that there is no 'YOUR CART IS EMPTY' text
        $Cart->click('#btn-minicart-close');
        $Cart->waitForElementNotVisible("//div[@class='item']//span[@class='counter qty']"); //Check than cart counter is not visible
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
        $cartProductQTY = $Cart->executeJS('return document.querySelectorAll(".select2-selection__rendered")[0].textContent');
        $Cart->click("#top-cart-btn-checkout");
        $Cart->waitPageLoad();
        $Cart->waitForElement('.product-item-name', 10);
        $Cart->click('.action.action-delete');
        $Cart->waitForText("Your Cart is Empty", 10, ".page-title");
        $cartCountAfter = $Cart->grabTextFrom('a.showcart span.counter-number');
        if ((int)$cartCountBefore - (int)$cartProductQTY !== (int)$cartCountAfter) {
            throw new Exception("QTY not correct");
        }
    }


    /**
     * @throws Exception
     */
    public function removeAllProductsFromCart() //Cycle with 'empty cart' check for remove all products from cart
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $Cart->click('a.showcart');
        $Cart->click("#top-cart-btn-checkout");
        $Cart->waitPageLoad();
        $cartIsNotEmpty = true; //Creating a variable for an empty cart
        while ($cartIsNotEmpty) { //Start cycle for clear cart
            try {
                $Cart->dontSee('Your Cart is Empty', ".page-title");
                $Cart->click("(//a[@class='action action-delete'])[1]"); //Remove first product from cart
                $cartIsNotEmpty = true; //Cart is not empty - false
            } catch (Exception $e) {
                $cartIsNotEmpty = false; //Cart is not empty - true
            }
        }
        $Cart->see('Your Cart is Empty', ".page-title");
        $Cart->waitForElementNotVisible("//div[@class='item']//span[@class='counter qty']");
    }

    /**
     * @throws Exception
     */
    public function addCouponToMiniCart() //TODO достать валидный купон и дописать тест
    {
        $Cart = $this;
        $Cart->waitPageLoad();
        $Cart->click('a.showcart');
        $Cart->waitForElement("#block-discount-heading", 10);
        $cartOrderTotalBefore = $Cart->grabTextFrom("#minicart-tax-order-total .price");
        $Cart->executeJS("document.querySelectorAll('#discount-minicart-content')[0].style.display = 'block'");
        $Cart->fillField('#discount-code-fake', \Page\Credentials::$COUPON);
        $Cart->click(".action.action-apply");
        $cartOrderTotalAfter = $Cart->grabTextFrom("#minicart-tax-order-total .price");
        if ((int)$cartOrderTotalBefore === (int)$cartOrderTotalAfter) {
            throw new Exception("Coupon not apply");
        }
    }
}