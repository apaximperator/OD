<?php

use Faker\Factory;
use Page\Credentials;

class CheckoutTester extends GlobalTester
{

    /**
     * @throws Exception
     */
    public function randomDeliveryMethod() //Select random delivery method and go to payment page
    {
        $I = $this;
        $deliveryMethodsCount = $I->getElementsCountByCssSelector('tr.delivery-method-row'); //Get delivery methods count
        $randomDeliveryMethodNumber = Factory::create(); //Run Faker create generator
        $randomDeliveryMethodNumber = $randomDeliveryMethodNumber->numberBetween(1, $deliveryMethodsCount); //Converted to string and generate numberBetween
        $I->wait(1);
        $I->click("//tr[@class='row delivery-method-row'][$randomDeliveryMethodNumber]"); //Click on random delivery method
        $I->waitAjaxLoad();
        $I->wait(1); //For full loading 'Order Success' block
        $I->fillField("//div[@class='payment-option-inner']//textarea", 'automation test'); //Enter 'Delivery Instructions' field
        $I->waitForElementClickable("//button[contains(@class,'continue')]"); //Waiting for 'Continue to review & payment' button is clickable
        $I->wait(1);
        $I->click("//button[contains(@class,'continue')]"); //Click on 'Continue to review & payment' button
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitPageLoad();
    }

    /**
     * @param $paymentMethod
     * @param $checkElementOnPaymentPage
     * @param $checkCurrentUrl
     * @throws Exception
     */
    public function paymentMethodByArgument($paymentMethod, $checkElementOnPaymentPage, $checkCurrentUrl) //Select payment method, check element and URL on payment method page by arguments
    {
        $I = $this;
        $I->moveMouseOver($paymentMethod); //Hover on payment method by argument
        $I->wait(1);
        $I->click($paymentMethod); //Click on payment method by selector
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitAjaxLoad();
        $I->waitForElementClickable("//button[@id='place-order-trigger']"); //Waiting for 'Complete Your Order' button is clickable
        $I->wait(3);
        $I->click("//button[@id='place-order-trigger']"); //Click on 'Complete Your Order' button
        $I->waitForElementVisible($checkElementOnPaymentPage, 120); //Check element on payment method page by selector
        $I->seeInCurrentUrl($checkCurrentUrl); //Check if current URL is contains by argument
    }

    /**
     * @param string $paymentMethod
     * @throws Exception
     */
    public function processCheckoutForLoggedUser(string $paymentMethod)
    {
        $I = $this;
        $I->amOnPage('/checkout');
        $I->waitForElementClickable("//tr[@class='row delivery-method-row'][1]", 10);
        $I->click("//tr[@class='row delivery-method-row'][1]"); //Click on random delivery method
        $I->waitAjaxLoad(15);
        $I->fillField("//div[@class='payment-option-inner']//textarea", 'automation test'); //Enter 'Delivery Instructions' field
        $I->waitForElementClickable("//button[contains(@class,'continue')]",10); //Waiting for 'Continue to review & payment' button is clickable
        $I->click("//button[contains(@class,'continue')]"); //Click on 'Continue to review & payment' button
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->wait(2);
        $I->executeJS("document.evaluate(\"//*/span[contains(text(), '$paymentMethod')]/ancestor::div/input[@name='payment[method]']\", document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue.click();");
//        $I->click("//*/span[contains(text(), '$paymentMethod')]/ancestor::div/input[@name='payment[method]']"); //Click on payment method by selector
        $I->waitForElementVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to appear
        $I->waitForElementNotVisible("//div[@class='loading-mask']", 30); //Waiting for preloader to disappear
        $I->waitForElementClickable("//button[@id='place-order-trigger']"); //Waiting for 'Complete Your Order' button is clickable
        $I->wait(3);
    }

    /**
     * @throws Exception
     */
    public function userGuestData()
    {
        $I = $this;
        $email = Factory::create();
        $email = $email->safeEmail; //Generate fake email
        $I->fillField("//input[contains(@id,'customer-email')]", $email); //Add email to the field
        $I->wait(5);
        $I->click("//div[contains(@name,'firstname')]"); //Click to first name field
        $I->wait(2);
        $I->fillField("//div[@name='shippingAddress.firstname']//input[@name='firstname']", 'Autotest'); //Add first name
        $I->wait(2);
        $I->click("//div[@name='shippingAddress.lastname']"); //Click to last name field
        $I->fillField("//div[@name='shippingAddress.lastname']//input[@name='lastname']", 'MKQA'); //Add last name
        $I->wait(2);
        $I->click("//div[contains(@name,'street.0')]"); //Click to shipping address field
        $I->wait(2);
        $I->fillField("//input[contains(@name,'street[0]')]", '1000'); //Add shipping index to the field
        $I->wait(5);
        $addressList = Factory::create();
        $addressList = $addressList->NumberBetween(1, 5);
        $I->click("//div[contains(@class,'autocomplete')]//li[@id][$addressList]"); //Choose random shipping address
        $I->wait(10);
        $I->waitPageLoad();
        $I->click("//div[@name='shippingAddress.telephone']"); //Click to  phone number field
        $I->fillField("//div[@name='shippingAddress.telephone']//input[@name='telephone']", Credentials::$PHONE); //Add phone number
        $I->wait(5);
    }

    /**
     * @throws Exception
     */
    public function clickAndCollect()
    {
        $I = $this;
        $I->click("//div[contains(@class,'click-collect')]"); //Click on 'Click And Collect' tab on checkout page
        $I->waitPageLoad();
        $I->seeElement("//div[@class='free-msg-content']"); //Check message
        $I->click("//span[contains(@id,'cc-store-selector')]"); //Click to Store selector
        $I->wait(3);
        $randomStoreFromList = Factory::create();
        $randomStoreFromList = $randomStoreFromList->numberBetween(1, 2);
        $foundStore = false;
        while (!$foundStore) { //Start cycle for check 'North Island' or 'South Island' radio button
            try {
                $I->seeElement("//ul[contains(@id,'cc-store')]//li[contains(@id,'cc-store')][$randomStoreFromList]"); //Choose one of stores
                $foundStore = true; //This delivery method one of 'North Island' or 'South Island' - true
                $I->click("//ul[contains(@id,'cc-store')]//li[contains(@id,'cc-store')][$randomStoreFromList]"); //Click on delivery method one of 'North Island' or 'South Island'
            } catch (Exception $e) {
                $randomStoreFromList = Factory::create();
                $randomStoreFromList = $randomStoreFromList->numberBetween(1, 2);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function bulkyDeliveryMethod()
    {
        $I = $this;
        $randomDeliveryMethod = Factory::create(); //Run Faker create generator
        $randomDeliveryMethod = $randomDeliveryMethod->numberBetween(1, 5); //Converted to string and generate numberBetween
        $foundBulkyDeliveryMethod = false; //Creating a variable for 'North Island' or 'South Island' radio button
        while (!$foundBulkyDeliveryMethod) { //Start cycle for check 'North Island' or 'South Island' radio button
            try {
                $I->seeElement("//tr[contains(@class,'delivery')][$randomDeliveryMethod]//td[contains(@class,'price')]//div[contains(@id,'bulky')]");
                $foundBulkyDeliveryMethod = true; //This delivery method one of 'North Island' or 'South Island '- true
                $I->click("//tr[contains(@class,'delivery')][$randomDeliveryMethod]//td[contains(@class,'price')]"); //Click on delivery method one of 'North Island' or 'South Island'
            } catch (Exception $e) {
                $randomDeliveryMethod = Factory::create(); //Run Faker create generator
                $randomDeliveryMethod = $randomDeliveryMethod->numberBetween(1, 5); //Converted to string and generate numberBetween
            }
        }
        $I->wait(5);
        $I->waitPageLoad();
        $I->click("//button[contains(@class,'continue')]"); //Click to "Continue" button
        $I->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function paymentCreditCardOrAfterPay()
    {
        $I = $this;
        $I->see('Select Payment Method:'); //Check text on Payment step
        $I->wait(5);
        $randomPaymentMethod = Factory::create(); //Run Faker create generator
        $randomPaymentMethod = $randomPaymentMethod->numberBetween(1, 5);
        $foundPaymentMethod = false;
        while (!$foundPaymentMethod) {
            try {
                $I->seeElement("//input[@id='paymentexpress_pxpay2' or @id='afterpaypayovertime'][$randomPaymentMethod]/parent::div/label"); //Check if payment methods AfterPay or Credit Card presents
                $foundPaymentMethod = true;
                $I->click("//input[@id='paymentexpress_pxpay2' or @id='afterpaypayovertime'][$randomPaymentMethod]/parent::div/label"); //Choose payment methods AfterPay or Credit Card
            } catch (Exception $e) {
                $randomPaymentMethod = Factory::create(); //Run Faker create generator
                $randomPaymentMethod = $randomPaymentMethod->numberBetween(1, 5); //Converted to string and generate numberBetween
            }
        }
        $I->wait(3);
        $I->waitPageLoad();
        $I->click("//button[contains(@id,'order')]"); //Click on "Create order" button
        $I->wait(7);
    }

}