<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session,
    Behat\MinkExtension\Context\RawMinkContext;

use Behat\MinkExtension\Context\MinkDictionary;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext implements ClosuredContextInterface {
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters) {
    }

    /**
     * steps/hooks 走査ディレクトリ取得
     * return array
     */
    protected function getScanDirectoryList() {
        $scan_directory_list[] = dirname(__DIR__);
        return $scan_directory_list;
    }
    
    /**
     * step定義処理を読み込む
     * @return array step定義処理パス配列
     */
    public function getStepDefinitionResources() {
        $scan_directory_list = $this->getScanDirectoryList();

        $step_list = array();
        foreach ($scan_directory_list as $scan_directory) {
            $step_list = array_merge($step_list, glob($scan_directory . '/steps/*.php'));
        }
        return $step_list;
    }

    /**
     * hook定義処理を読み込む
     * @return array hook定義処理パス配列
     */
    public function getHookDefinitionResources($arg = null) {
        $scan_directory_list = $this->getScanDirectoryList();

        $step_list = array();
        foreach ($scan_directory_list as $scan_directory) {
            $step_list = array_merge($step_list, glob($scan_directory . '/hooks/*.php'));
        }
        return $step_list;
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//
}
