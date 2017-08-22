<?php

namespace wcf\system\user\notification\object;

use wcf\data\user\notification\custom\CustomNotification;
use wcf\data\DatabaseObjectDecorator;

/**
 * Class CustomNotificationNotificationObject
 *
 * @package wcf\system\user\notification\object
 *
 * @mixin CustomNotification
 * @method CustomNotification getDecoratedObject()
 */
class CustomNotificationNotificationObject extends DatabaseObjectDecorator implements IUserNotificationObject {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = CustomNotification::class;
	
	/**
	 * @inheritDoc
	 */
	public function getObjectID() {
		return $this->getDecoratedObject()->notificationID;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->getDecoratedObject()->getTitle();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getURL() {
		return $this->getDecoratedObject()->url;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getAuthorID() {
		return $this->getDecoratedObject()->userID;
	}
}
