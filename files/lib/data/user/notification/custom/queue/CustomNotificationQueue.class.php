<?php

namespace wcf\data\user\notification\custom\queue;

use wcf\data\DatabaseObject;
use wcf\data\user\notification\custom\CustomNotification;
use wcf\data\user\User;

/**
 * Class CustomNotificationQueue
 *
 * @package wcf\data\user\notification\custom\queue
 *
 * @property-read integer $itemID
 * @property-read integer $notificationID
 * @property-read integer $userID
 * @property-read boolean $errored
 * @property-read string  $error
 */
class CustomNotificationQueue extends DatabaseObject {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'itemID';
	
	/**
	 * @var \wcf\data\user\User
	 */
	protected $user;
	
	/**
	 * @var \wcf\data\user\notification\custom\CustomNotification
	 */
	protected $notification;
	
	/**
	 * @return CustomNotification
	 */
	public function getNotification() {
		if ($this->notification === null) {
			$this->notification = new CustomNotification($this->notificationID);
		}
		
		return $this->notification;
	}
	
	/**
	 * @return User
	 */
	public function getUser() {
		if ($this->user === null) {
			$this->user = new User($this->userID);
		}
		
		return $this->user;
	}
}
