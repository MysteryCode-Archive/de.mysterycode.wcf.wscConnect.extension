<?php

namespace wcf\acp\page;

use wcf\data\user\notification\custom\CustomNotificationList;
use wcf\page\SortablePage;

/**
 * Class CustomNotificationListPage
 *
 * @package wcf\page
 * @property CustomNotificationList $objectList
 */
class CustomNotificationListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.user.wscConnect.notification.custom.canView'];
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_WSC_CONNECT'];
	
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.customnotifications.list';
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = CustomNotificationList::class;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'time';
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = [
		'username',
		'subject',
		'message',
		'url',
		'time'
	];
}
