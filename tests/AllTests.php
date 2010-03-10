<?php

// add lib to global include line
set_include_path(get_include_path() . PATH_SEPARATOR .
        dirname(__FILE__) . '/../library/'
);

require_once 'PHPUnit/Framework.php';
require_once 'MapTests/ItemTest.php';
require_once 'MapTests/BuilderTests/ParserTest.php';

class AllTests extends PHPUnit_Framework_TestSuite {

    protected function setUp() {
    
    }

    public static function suite() {

        $suite = new AllTests();

        $suite->addTestSuite('PdTests_MapTests_ItemTest');
        
        $suite->addTestSuite('PdTests_MapTests_BuilderTests_ParserTest');

        return $suite;
    }


}