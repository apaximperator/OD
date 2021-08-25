<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */

/**
 * Define custom actions here
 */

use Codeception\Actor;
use Faker\Factory;

class AcceptanceTester extends Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return Factory::create();
    }

    /**
     * @param $selector
     * @return mixed
     */
    public function getElementsCountByCssSelector($selector)
    {
        $I = $this;
        return $I->executeJS("return document.querySelectorAll('$selector').length");
    }

    private static $cjq = true;

    /**
     * Connect JQ on site
     */
    public function connectJq()
    {
        $I = $this;
        if (self::$cjq) {
            $I->executeJS("var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'http://code.jquery.com/jquery-1.8.3.js';
            document.head.appendChild(script);
            ");
            self::$cjq = false;
        }
    }

}