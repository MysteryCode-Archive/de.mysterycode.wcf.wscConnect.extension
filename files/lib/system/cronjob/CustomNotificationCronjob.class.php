<?php

namespace wcf\system\cronjob;

use wcf\data\cronjob\Cronjob;
use wcf\data\user\notification\custom\CustomNotification;
use wcf\data\user\notification\custom\queue\CustomNotificationQueueAction;
use wcf\data\user\notification\custom\queue\CustomNotificationQueueList;

class CustomNotificationCronjob extends AbstractCronjob {
	public function execute(Cronjob $cronjob) {
		parent::execute($cronjob);
		
		$itemList = new CustomNotificationQueueList();
		$itemList->getConditionBuilder()->add('user_notification_custom_queue.errored = 0');
		$itemList->sqlLimit = CustomNotification::MAX_API_REQUESTS;
		$itemList->readObjects();
		$items = $itemList->getObjects();
		
		if (!empty($items)) {
			$itemAction = new CustomNotificationQueueAction($items, 'fire');
			$itemAction->executeAction();
		}
	}
}
