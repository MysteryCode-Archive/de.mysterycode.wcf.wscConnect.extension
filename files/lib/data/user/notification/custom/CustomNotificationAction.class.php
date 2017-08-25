<?php

namespace wcf\data\user\notification\custom;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\user\notification\event\CustomNotificationNotificationEvent;
use wcf\system\user\notification\object\CustomNotificationNotificationObject;
use wcf\system\user\notification\UserNotificationHandler;

/**
 * Class CustomNotificationAction
 *
 * @package wcf\data\user\notification\custom
 *
 * @property CustomNotificationEditor[] $objects
 * @method  CustomNotificationEditor[] getObjects()
 * @method  CustomNotificationEditor getSingleObject()
 */
class CustomNotificationAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
	public function create() {
		if (!empty($this->parameters['htmlInputProcessor']) && empty($this->parameters['data']['message'])) {
			$this->parameters['data']['message'] = $this->parameters['htmlInputProcessor']->getHtml();
		}
		
		/** @var CustomNotification $notification */
		$notification = parent::create();
		
		if (empty($this->parameters['silentCreation'])) {
			$notificationObject = new CustomNotificationNotificationObject($notification);
			UserNotificationHandler::getInstance()->fireEvent('custom', 'de.mysterycode.wcf.wscConnect.notification.custom', $notificationObject, $notification->getRecipientUserIDs());
		}
		
		return $notification;
	}
	
	/**
	 * Validates the fire action
	 */
	public function validateFire() {
		$this->readObjects();
		
		if (empty($this->objects)) throw new UserInputException('objectIDs');
	}
	
	/**
	 * Fires the WSC-notification for the current notification
	 */
	public function fire() {
		foreach ($this->objects as $object) {
			$notificationEvent = new CustomNotificationNotificationEvent($object);
			UserNotificationHandler::getInstance()->fireEvent('custom', 'de.mysterycode.wcf.wscConnect.notification.custom', $notificationEvent, $object->getRecipientUserIDs());
		}
	}
}
