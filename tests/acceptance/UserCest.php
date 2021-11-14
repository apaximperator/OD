<?php

class UserCest
{
    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function userLogin(GlobalTester $G)
    {
        $G->amOnPage('/');
        $G->login();
        $G->logout();
    }

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function userRegister(GlobalTester $G)
    {
        $G->amOnPage('/');
        $user = $G->registration();
        $G->login($user['firstname'], $user['email'], $user['password']);
        $G->logout();
    }

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function addressForNewUser(GlobalTester $G)
    {
        $G->amOnPage('/');
        $user = $G->registration();
        $G->login($user['firstname'], $user['email'], $user['password']);
        $G->waitForElementClickable('a[class="action link-account customer-account-menu"]');
        $G->click('a[class="action link-account customer-account-menu"]');
        $G->waitForElementClickable("//ul[@id='customer-account-menu']/li/a[contains(.,'My Addresses')]");
        $G->click("//ul[@id='customer-account-menu']/li/a[contains(.,'My Addresses')]");
        $G->waitPageLoad();
        $G->waitForElementClickable('button[class="action save primary"]');
        $G->click("//input[@id='street_1']"); //Click to shipping address field
        $G->wait(2);
        $G->fillField("//input[@id='street_1']", '1000'); //Add shipping index to the field
        $G->wait(5);
        $addressList = rand(1, 5);
        $G->click("//ul/li[@id][$addressList]"); //Choose random shipping address
        $G->wait(2);
        $G->waitPageLoad();
        $G->click("//input[@id='telephone']"); //Click to  phone number field
        $G->wait(2);
        $G->fillField("//input[@id='telephone']", '3222333'); //Add phone number
        $G->click('button[class="action save primary"]');
        $G->waitPageLoad();
        $G->waitForElementVisible('div[class="block block-addresses-default"]');
    }

    /**
     * @param GlobalTester $G
     * @throws Exception
     */
    public function addressForExistUser(GlobalTester $G)
    {
        $G->amOnPage('/');
        $G->login();
        $G->addAdditionalAddresses();
        $G->editAdditionalAddresses();
        $G->clearAdditionalAddresses();
    }


}
