<?php
namespace Step\Acceptance;

class Admin extends \AcceptanceTester
{

    public function loginAsAdmin()
    {
        $I = $this;
	    $I->amOnPage( '/wp-admin/' );
	    $I->see( 'Username or Email Address' );
	    $I->fillField( '#user_login', 'admin' );
	    $I->fillField( '#user_pass', 'admin' );
	    $I->checkOption( '#rememberme' );
	    $I->click( '#wp-submit' );
    }
}
