<?php

namespace wcf\page;

use wcf\acp\page\UserListPage;

/**
 * Class WscConnectUserList
 *
 * @package wcf\page
 */
class WscConnectUserListPage extends UserListPage {
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.user.wscConnect.notification.user.canView'];
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_WSC_CONNECT'];
	
	/**
	 * @inheritDoc
	 */
	public $templateName = 'userList';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['userID', 'registrationDate', 'username', 'lastActivityTime', 'profileHits', 'activityPoints', 'likesReceived', 'wscConnectLoginDevice', 'wscConnectLoginTime'];
	
	protected function readUsers() {
		parent::readUsers();
		
		//$this->conditions->add('user_table.wscConnectToken != ?', ['']);
		$this->conditions->add('user_table.wscConnectToken IS NOT NULL');
		$this->conditions->add('user_table.wscConnectLoginTime > 0');
	}
}
