<?php


use Step\Acceptance\Admin;

class AdminCest {
	/**
	 * @param Admin $I
	 *
	 * @throws Exception
	 */
	public function adminPageTest( Admin $I ) {
		$I->loginAsAdmin();
		$I->seeElement('#toplevel_page_mcs > a');
		$I->click( '#toplevel_page_mcs > a' );
		$I->waitForElement( '#react-admin-title > span' );
		$I->see( 'Countries', '#react-admin-title > span' );
	}
}
