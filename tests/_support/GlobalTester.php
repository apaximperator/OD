<?php

use Facebook\WebDriver\WebDriverKeys;
use Faker\Factory;
use Page\Credentials;

class GlobalTester extends AcceptanceTester
{

    /**
     * @param string $firstname
     * @param string $email
     * @param string $password
     * @throws Exception
     */
    public function login(string $firstname = "", string $email = "", string $password = "")
    {
        $I = $this;
        $I->connectJq();
        if ($email == "") {
            $email = Credentials::$EMAIL;
        }
        if ($password == "") {
            $password = Credentials::$PASSWORD;
        }
        if ($firstname == "") {
            $firstname = Credentials::$FIRSTNAME;
        }
        try {
            $I->waitForText("HI, " . strtoupper($firstname), 10, "a.customer-account-menu");
        } catch (Exception $e) {
            $I->waitForElementVisible("span.social-login", 30);
            $I->click("span.social-login");
            $I->wait(5);
            $I->fillField("#email", $email);
            $I->fillField("#pass", $password);
            $I->click("#bt-social-login");
            $I->waitForText("PLEASE WAIT...", 30, ".mesg-request");
            $I->waitForText("HI, " . strtoupper($firstname), 30, "a.customer-account-menu");
        }
    }

    /**
     * @throws Exception
     */
    public function logout()
    {
        $I = $this;
        $I->connectJq();
        try {
            $I->waitForElementVisible("span.social-login", 10);
        } catch (Exception $e) {
            $I->waitForElementClickable("a.customer-account-menu");
            $I->click("a.customer-account-menu");
            $I->waitForElementVisible("li.last a");
            $I->click("li.last a");

            $I->waitForElementVisible("//h1//span[contains(text(),'You are signed out')]"); //Check text on logoutSuccess page
            $I->seeCurrentUrlEquals('/customer/account/logoutSuccess/'); //Check 'logout success' page URL
            $I->waitForElementNotVisible("//h1//span[contains(text(),'You are signed out')]", 30); //Check that this element is disappear
            $I->waitPageLoad();
            $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait guest 'My Account' section
            $I->seeCurrentUrlEquals('/'); //Check homepage URL
        }
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @return array(str, str, str)
     * @throws Exception
     */
    public function registration(string $firstname = '', string $lastname = '', string $email = '', string $password = ''): array
    {
        $I = $this;
        if ($email == "") {
            $email = Factory::create()->safeEmail;
        }
        if ($password == "") {
            $password = Credentials::$PASSWORD;
        }
        if ($firstname == "") {
            $firstname = Credentials::$FIRSTNAME;
        }
        if ($lastname == "") {
            $lastname = Credentials::$LASTNAME;
        }
        $I->connectJq();
        $I->waitForElementVisible(".create-account-popup", 30);
        $I->click(".create-account-popup");
        $I->waitForElementVisible('#bt-social-create');
        $I->fillField("#firstname", $firstname);
        $I->fillField("#lastname", $lastname);
        $I->fillField("#bss_email_address", $email);
        $I->fillField("#password", $password);
        $I->click("#bt-social-create");
        $I->waitForText('PLEASE WAIT...', 30, ".mesg-request");
        $I->waitForElementNotVisible('#bt-social-create', 10);
        $I->waitForText("HI, " . strtoupper($firstname), 30, "a.customer-account-menu");
        return ['firstname' => $firstname, 'email' => $email, 'password' => $password];
    }

    /**
     * @param int $time
     * @throws Exception
     */
    public function closePopup(int $time = 30)
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#webChannel .title", $time);
        $I->click("div[data-wps-popup-close]");
        $I->waitForElementNotVisible("#webChannel .title", 5);
    }


    /**
     * @param string $searchString
     * @throws Exception
     */
    public function searchByText(string $searchString = "test")
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#search");
        $I->click("#search");
        $I->fillField("#search", $searchString);
        $I->waitForElementClickable("#klevuSearchSuggest ul li a", 10);
        $I->click("#klevuSearchSuggest ul li a");
        $I->amOnPage("/");
    }

    /**
     * @param string $searchString
     * @throws Exception
     */
    public function searchResultByText(string $searchString = "test")
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#search");
        $I->click("#search");
        $I->fillField("#search", $searchString);
        $I->pressKey('#search', WebDriverKeys::ENTER);
        $I->waitForText("Search results for: '$searchString'", 10, "span.base");
        $I->amOnPage("/");
    }

    /**
     * @throws Exception
     */
    public function searchEmptyResult()
    {
        $I = $this;
        $I->connectJq();
        $I->waitForElementVisible("#search");
        $I->click("#search");
        $I->fillField("#search", "qwerreqwerqwer");
        $I->waitForText("Please try another search term...", 30, ".klevuNoResults-message");
        $I->amOnPage("/");
    }


    /**
     * @throws Exception
     */
    public function blogAndLoadMoreButton()
    {
//        $I = $this;
//        $I->waitForElementVisible("//div[contains(@class,'login')]//a[contains(@class,'login')]", 30); //Wait 'My Account' section
//        $I->seeElement("//span[contains(text(),'Community')]"); //Check 'Community' category
//        $I->click("//li[contains(@class,'level0')][last()]"); //Click on 'Community' category
//        $I->waitPageLoad();
//        $I->waitForElementVisible("//ol[@class='post-list']"); //Check post list
//        $I->waitForElementClickable("//button[contains(@class,'lazyload')]"); //Waiting for 'Load more' button is clickable
//        $I->click("//button[contains(@class,'lazyload')]"); //Click on 'Load more' button
//        $I->waitForElementVisible("//img[@class='posts-loader mfblog-show-onload']"); //Waiting for preloader to appear
//        $I->waitForElementNotVisible("//img[@class='posts-loader mfblog-show-onload']"); //Waiting for preloader to disappear
//        $I->waitForElementVisible("//li[contains(@class,'post-holder')][10]"); //Check 10th blog post availability
//        $I->click("//li[contains(@class,'post-holder')][10]"); //Click on 10th blog post
//        $I->waitPageLoad();
//        $I->waitForElementVisible("//h1[@class='post-view__title']"); //Check h1 title
    }

}