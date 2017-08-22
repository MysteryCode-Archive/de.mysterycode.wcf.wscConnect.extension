<?php

namespace wcf\acp\page;

use wcf\page\AbstractPage;

/**
 * Class WscConnectUserList
 *
 * @package wcf\page
 */
class WscConnectUserListPage extends UserListPage {
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.user.wscConnect.user.canView'];
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_WSC_CONNECT'];
	
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.wscConnectUsers';
	
	/**
	 * @inheritDoc
	 */
	public $templateName = 'userList';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['userID', 'registrationDate', 'username', 'lastActivityTime', 'profileHits', 'activityPoints', 'likesReceived', 'wscConnectLoginDevice', 'wscConnectLoginTime'];
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		//$this->conditions->add('user_table.wscConnectToken != ?', ['']);
		$this->conditions->add('user_table.wscConnectToken IS NOT NULL');
		//$this->conditions->add('user_table.wscConnectLoginTime > 0');
	}
	
	/**
	 * @inheritDoc
	 */
	public function show() {
		$this->activeMenuItem = 'wcf.acp.menu.link.user.wscConnectUsers';
		
		AbstractPage::show();
	}
}
