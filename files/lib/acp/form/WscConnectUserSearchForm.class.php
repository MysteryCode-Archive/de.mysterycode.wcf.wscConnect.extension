<?php

namespace wcf\acp\form;

/**
 * Class WscConnectUserSearchForm
 *
 * @package wcf\acp\form
 */
class WscConnectUserSearchForm extends UserSearchForm {
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
	public $activeMenuItem = 'wcf.acp.menu.link.user.wscConnectUsers.search';
	
	/**
	 * @inheritDoc
	 */
	public $templateName = 'userSearch';
	
	/**
	 * @inheritDoc
	 */
	protected function search() {
		parent::search();
		
		//$this->userList->getConditionBuilder()->add('user_table.wscConnectToken != ?', ['']);
		$this->userList->getConditionBuilder()->add('user_table.wscConnectToken IS NOT NULL');
		//$this->userList->getConditionBuilder()->add('user_table.wscConnectLoginTime > 0');
		
		// read again
		$this->userList->readObjectIDs();
	}
}
