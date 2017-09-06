<?php

namespace wcf\data\user\notification\custom;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\user\notification\custom\queue\CustomNotificationQueueAction;
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
 * @method   CustomNotificationEditor[] getObjects()
 * @method   CustomNotificationEditor   getSingleObject()
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
			$sendAction = new self([$notification], 'fire');
			$sendAction->executeAction();
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
		$createdItems = [];
		
		foreach ($this->objects as $object) {
			if ($object->isNotification) {
				$notificationObject = new CustomNotificationNotificationObject($object->getDecoratedObject());
				UserNotificationHandler::getInstance()->fireEvent('custom', 'de.mysterycode.wcf.wscConnect.notification.custom', $notificationObject, $object->getRecipientUserIDs());
				
				$object->update([
					'hasSucceeded' => 1
				]);
			}
			else if (preg_match('/\{\$(' . implode('|', CustomNotification::SUPPORTED_VARIABLES) . ')\$\}/', $object->message)) {
				foreach ($object->getRecipientUserIDs() as $userID) {
					$itemAction = new CustomNotificationQueueAction([], 'create', [
						'data' => [
							'notificationID' => $object->notificationID,
							'isNotification' => $object->isNotification,
							'userID' => $userID
						]
					]);
					$createdItems[] = $itemAction->executeAction()['returnValues'];
				}
			}
			else {
				$itemAction = new CustomNotificationQueueAction([], 'create', [
					'data' => [
						'notificationID' => $object->notificationID,
						'isNotification' => $object->isNotification
					]
				]);
				$createdItems[] = $itemAction->executeAction()['returnValues'];
			}
		}
		
		if (count($createdItems) <= CustomNotification::MAX_API_REQUESTS) {
			$itemAction = new CustomNotificationQueueAction($createdItems, 'fire');
			$itemAction->executeAction();
		} else {
			$maxItems = CustomNotification::MAX_API_REQUESTS + 1;
			$itemAction = new CustomNotificationQueueAction(array_slice($createdItems, 0, $maxItems), 'fire');
			$itemAction->executeAction();
		}
	}
}
