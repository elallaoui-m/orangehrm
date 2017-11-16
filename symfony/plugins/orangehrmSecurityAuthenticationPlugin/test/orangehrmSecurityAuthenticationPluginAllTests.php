<?php

class orangehrmSecurityAuthenticationPluginAllTests {

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('orangehrmSecurityAuthenticationPluginAllTest');

        $basePath = dirname(__FILE__);

        $suite->addTestFile($basePath . '/utility/SecurityAuthenticationConfigServiceTest.php');
        $suite->addTestFile($basePath . '/utility/PasswordHelperTest.php');

        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
}

if (PHPUnit_MAIN_METHOD == 'orangehrmSecurityAuthenticationPluginAllTests::main') {
    orangehrmSecurityAuthenticationPluginAllTests::main();
}
