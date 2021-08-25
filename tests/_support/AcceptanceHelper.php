<?php

namespace tests\_support;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\TestCase;

/**
 *
 */
class AcceptanceHelper extends Module
{
    private $webDriver = null;
    private $webDriverModule = null;

    /**
     * Event hook before a test starts.
     *
     * @param TestCase $test
     *
     * @throws \Exception
     */
    public function _before(TestCase $test)
    {
        if (!$this->hasModule('WebDriver') && !$this->hasModule('Selenium2')) {
            throw new \Exception('PageWait uses the WebDriver. Please be sure that this module is activated.');
        }

        // Use WebDriver
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule = $this->getModule('WebDriver');
            $this->webDriver = $this->webDriverModule->webDriver;
        }
    }

    /**
     * Waiting for ajax to load.
     *
     * @param $timeout
     */
    public function waitAjaxLoad($timeout = 30)
    {
        $this->webDriverModule->waitForJS('return !!window.jQuery && window.jQuery.active == 0;', $timeout);
        $this->webDriverModule->wait(1);
    }

    /**
     * Waiting for the page to load.
     *
     * @param $timeout
     */
    public function waitPageLoad($timeout = 30)
    {
        $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
        $this->waitAjaxLoad($timeout);
    }

    /**
     * Go to the page.
     *
     * @param $link
     * @param $timeout
     */
    public function amOnPage($link, $timeout = 30)
    {
        $this->webDriverModule->amOnPage($link);
        $this->waitPageLoad($timeout);
    }

    /**
     * @param $identifier
     * @param null $elementID
     * @param null $excludeElements
     * @param false $element
     * @throws ModuleException
     */
    public function dontSeeVisualChanges($identifier, $elementID = null, $excludeElements = null, $element = false)
    {
        if ($element !== false) {
            $this->webDriverModule->moveMouseOver($element);
        }
        $this->getModule('VisualCeption')->dontSeeVisualChanges($identifier, $elementID, $excludeElements);
    }

    /**
     * Check the absence of errors in the console.
     */
    public function dontSeeJsError()
    {
        $logs = $this->webDriver->manage()->getLog('browser');
        foreach ($logs as $log) {
            if ($log['level'] == 'SEVERE') {
                throw new ModuleException($this, 'Some error in JavaScript: ' . json_encode($log));
            }
        }
    }

}