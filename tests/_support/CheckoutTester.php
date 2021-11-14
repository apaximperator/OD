<?php

use Faker\Factory;
use Page\Credentials;

class CheckoutTester extends GlobalTester
{

    /**
     * @throws Exception
     */
    public function goToCheckout()
    {
        $Ch = $this;
        $Ch->waitPageLoad();
        $Ch->click('a.showcart');
        $Ch->waitForElement('.product-item__name a', 10);
        $Ch->waitForElementClickable('#top-cart-btn-checkout', 10);
        $Ch->click("#top-cart-btn-checkout");
        $Ch->waitPageLoad();
        $Ch->executeJS("document.querySelectorAll('.item .action.primary.checkout')[1].click()");
        $Ch->waitPageLoad();
        $Ch->waitForElement('.product-item__name', 10);
    }

    public function userGuestData()
    {
        $Ch = $this;
        $email = Factory::create()->safeEmail; //Generate fake email
        $Ch->fillField("//input[contains(@id,'customer-email')]", $email); //Add email to the field
        $Ch->wait(5);
        $Ch->click("//div[contains(@name,'firstname')]"); //Click to first name field
        $Ch->wait(2);
        $Ch->fillField("//div[@name='shippingAddress.firstname']//input[@name='firstname']", Credentials::$FIRSTNAME); //Add first name
        $Ch->wait(2);
        $Ch->click("//div[@name='shippingAddress.lastname']"); //Click to last name field
        $Ch->fillField("//div[@name='shippingAddress.lastname']//input[@name='lastname']", Credentials::$LASTNAME); //Add last name
        $Ch->wait(2);
        $Ch->click("//div[@name='shippingAddress.company']"); //Click to company field
        $Ch->wait(2);
        $Ch->fillField("//div[@name='shippingAddress.company']/div/input[@name='company']", Credentials::$COMPANY);
        $Ch->wait(2);
        $Ch->click("//div[contains(@name,'street.0')]"); //Click to shipping address field
        $Ch->wait(2);
        $Ch->fillField("//input[contains(@name,'street[0]')]", '1000'); //Add shipping index to the field
        $Ch->wait(5);
        $addressList = rand(1, 5);
        $Ch->click("//ul/li[@id][$addressList]"); //Choose random shipping address
        $Ch->wait(10);
        $Ch->waitPageLoad();
        $Ch->click("//div[@name='shippingAddress.telephone']"); //Click to  phone number field
        $Ch->fillField("//div[@name='shippingAddress.telephone']//input[@name='telephone']", Credentials::$PHONE); //Add phone number
        $Ch->wait(5);
    }

    /**
     * @throws Exception
     */
    public function loginFromCheckOut()
    {
        $Ch = $this;
        $Ch->waitPageLoad();
        $Ch->fillField('#customer-email', Credentials::$EMAIL);
        $Ch->wait(5);
        $Ch->waitAjaxLoad();
        $Ch->seeInField('#customer-email', Credentials::$EMAIL);
        $Ch->waitForElement('#customer-password', 10);
        $Ch->fillField("#customer-password", Credentials::$PASSWORD);
        $Ch->waitAjaxLoad();
        $Ch->seeInField('#customer-password', Credentials::$PASSWORD);
        $Ch->waitAjaxLoad();
        $Ch->waitForElementClickable('button[class="action secondary login"]', 10);
        $Ch->click('button[class="action secondary login"]');
        $Ch->waitPageLoad();
        $Ch->waitForElementNotVisible('#customer-email', 5);

    }

    /**
     * @throws Exception
     */
    public function randomDeliveryMethod() //Select random delivery method and go to payment page
    {
        $Ch = $this;
        $Ch->waitPageLoad();
        $Ch->waitAjaxLoad();
        $Ch->waitForElementVisible('div.shipping-item__name');
        $deliveryMethodsCount = $Ch->getElementsCountByCssSelector('div.shipping-item__name'); //Get delivery methods count
        $randomDeliveryMethodNumber = rand(1, $deliveryMethodsCount); //Run Faker create generator
        $Ch->click("(//div[@class='shipping-item__name'])[$randomDeliveryMethodNumber]"); //Click on random delivery method
        $Ch->waitAjaxLoad();
        $Ch->wait(1); //For full loading 'Order Success' block
        $Ch->fillField("//div[@class='payment-option-inner']//textarea", 'automation test'); //Enter 'Delivery Instructions' field
        $Ch->waitForElementClickable("//button[contains(@class,'continue')]"); //Waiting for 'Continue to review & payment' button is clickable
        $Ch->wait(1);
        $Ch->click("//button[contains(@class,'continue')]"); //Click on 'Continue to review & payment' button
        $Ch->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $Ch->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $Ch->waitPageLoad();
    }

    /**
     * @param string $paymentMethod
     * @param string $checkTitleOnPaymentPage
     * @throws Exception
     */
    public function paymentMethodByArgument(string $paymentMethod, string $checkTitleOnPaymentPage) //Select payment method by arguments
    {
        $Ch = $this;
        $Ch->wait(1);
        $Ch->waitPageLoad();
        $Ch->waitAjaxLoad();
        $Ch->moveMouseOver("//input[@id='" . $paymentMethod . "']/parent::div/label"); //Hover on payment method by argument
        $Ch->wait(1);
        $Ch->click("//input[@id='" . $paymentMethod . "']/parent::div/label"); //Click on payment method by selector
        $Ch->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $Ch->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $Ch->waitAjaxLoad();
        $Ch->clickOnElementByCssSelector('div[class="checkout-agreement field choice required"] input');
        $Ch->wait(3);
        $Ch->waitForElementClickable("//button[@id='checkout-place-order']"); //Waiting for 'Complete Your Order' button is clickable
        $Ch->click("//button[@id='checkout-place-order']"); //Click on 'Complete Your Order' button
        $Ch->wait(10);
        $Ch->seeInTitle($checkTitleOnPaymentPage, 120);
    }

    /**
     * @param string $paymentMethod
     * @param string $checkTitleOnPaymentPage
     * @throws Exception
     */
    public function processCheckoutForLoggedUser(string $paymentMethod, string $checkTitleOnPaymentPage)
    {
        $Ch = $this;
        $Ch->goToCheckout();
        $Ch->randomDeliveryMethod();
        $Ch->paymentMethodByArgument($paymentMethod, $checkTitleOnPaymentPage);
    }

}