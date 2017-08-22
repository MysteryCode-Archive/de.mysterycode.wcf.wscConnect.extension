<?php

namespace wcf\system\user\notification\event;

/**
 * Class CustomNotificationNotificationEvent
 *
 * @package wcf\system\user\notification\event
 *
 * @property \wcf\system\user\notification\object\CustomNotificationNotificationObject $userNotificationObject
 */
class CustomNotificationNotificationEvent extends AbstractUserNotificationEvent {
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->userNotificationObject->getDecoratedObject()->getTitle();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getMessage() {
		return $this->userNotificationObject->getDecoratedObject()->getMessage();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getLink() {
		return $this->userNotificationObject->getDecoratedObject()->url;
	}
	
	/**
	 * @inheritDoc
	 */
	public function supportsEmailNotification() {
		return false;
	}
}
