<?php

namespace wcf\system\user\notification\object\type;

use wcf\data\user\notification\custom\CustomNotification;
use wcf\data\user\notification\custom\CustomNotificationList;
use wcf\system\user\notification\object\CustomNotificationNotificationObject;

/**
 * Class CustomNotificationNotificationObjectType
 *
 * @package wcf\system\user\notification\object\type
 */
class CustomNotificationNotificationObjectType extends AbstractUserNotificationObjectType {
	/**
	 * @inheritDoc
	 */
	protected static $decoratorClassName = CustomNotificationNotificationObject::class;
	
	/**
	 * @inheritDoc
	 */
	protected static $objectClassName = CustomNotification::class;
	
	/**
	 * @inheritDoc
	 */
	protected static $objectListClassName = CustomNotificationList::class;
}
