<?php

namespace wcf\data\user\notification\custom;

use wcf\data\DatabaseObjectList;

/**
 * Class CustomNotificationList
 *
 * @package wcf\data\user\notification\custom
 *
 * @property CustomNotification[] $objects
 * @method CustomNotification[] getObjects()
 */
class CustomNotificationList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'user_notification_custom';
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'notificationID';
}
