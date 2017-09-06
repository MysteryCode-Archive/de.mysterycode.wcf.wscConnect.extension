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
	protected static $apiURL = 'https://api.wsc-connect.com/messages/' + WSC_CONNECT_APP_ID;
	
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
		$this->readObjects();
		
		WCF::getDB()->beginTransaction();
		foreach ($this->getObjects() as $item) {
			$object = $item->getNotification();
			if ($item->userID) {
				$userIDs = [$item->getUser()];
				$message = $object->getPersonalizedMessage($item->getUser());
			}
			else{
				$userIDs = $object->getRecipientUserIDs();
				$message = $object->getMessage(true);
			}
			
			$request = new HTTPRequest(self::$apiURL, [], [
				'appSecret' => WSC_CONNECT_APP_SECRET,
				'title' => $object->getTitle(),
				'message' => $message,
				'users' => $userIDs
			]);
			try {
				$request->execute();
				
				$itemEditor = new CustomNotificationQueueEditor($item);
				$itemEditor->delete();
				
				//$notificationEditor = new CustomNotificationEditor($object);
				//$notificationEditor->update([
				//	'hasSucceeded' => 1
				//]);
			}
			catch (\Exception $e) {
				$itemEditor = new CustomNotificationQueueEditor($item);
				$itemEditor->update([
					'errored' => 1,
					'error' => @serialize($e)
				]);
			}
		}
		WCF::getDB()->commitTransaction();
		
		
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
