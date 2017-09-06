<?php

namespace wcf\data\user\notification\custom\queue;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\WCF;
use wcf\util\HTTPRequest;

/**
 * Class CustomNotificationQueueAction
 *
 * @package wcf\data\user\notification\custom\queue
 *
 * @property CustomNotificationQueueEditor[] $objects
 * @method   CustomNotificationQueueEditor[] getObjects()
 * @method   CustomNotificationQueueEditor   getSingleObject()()
 */
class CustomNotificationQueueAction extends AbstractDatabaseObjectAction {
	/**
	 * @var string
	 */
	protected static $apiURL = 'https://api.wsc-connect.com/messages/' . WSC_CONNECT_APP_ID;
	
	/**
	 * Validates the fire action
	 */
	public function validateFire() {
		// do nothing
	}
	
	/**
	 * Fires the push notification
	 */
	public function fire() {
		if (empty($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->getObjects() as $item) {
			$object = $item->getNotification();
			if ($item->userID) {
				$userIDs = [$item->getUser()];
				$message = $object->getPersonalizedMessage($item->getUser(), 'text/plain');
			}
			else {
				$userIDs = $object->getRecipientUserIDs();
				$message = $object->getMessage(true, 'text/plain');
			}
			
			$request = new HTTPRequest(self::$apiURL, [], [
				'appSecret' => WSC_CONNECT_APP_SECRET,
				'title' => $object->getTitle(),
				'message' => $message,
				'users' => $userIDs
			]);
			try {
				$request->execute();
				
				$item->delete();
			}
			catch (\Exception $e) {
				$item->update([
					'errored' => 1,
					'error' => @serialize($e)
				]);
			}
		}
		
		$sql = "UPDATE  wcf" . WCF_N . "_user_notification_custom n
			SET     n.hasSucceeded = ?
			WHERE   n.notificationID NOT IN (
				SELECT  i.notificationID
				FROM    wcf" . WCF_N . "_user_notification_custom_queue i
			);";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([1]);
	}
}
