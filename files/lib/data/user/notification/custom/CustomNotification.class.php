<?php

namespace wcf\data\user\notification\custom;

use wcf\data\DatabaseObject;
use wcf\data\user\User;
use wcf\system\WCF;

/**
 * Class CustomNotification
 *
 * @package wcf\data\user\notification\custom
 *
 * @property-read integer $notificationID
 * @property-read string  $subject
 * @property-read string  $message
 * @property-read string  $url
 * @property-read integer $userID
 * @property-read string  $username
 * @property-read string  $recipientUsernames
 * @property-read integer $time
 */
class CustomNotification extends DatabaseObject {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'user_notification_custom';
	
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableIndexName = 'notificationID';
	
	/**
	 * @return string
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->subject);
	}
	
	/**
	 * @return string
	 */
	public function getMessage() {
		return WCF::getLanguage()->get($this->message);
	}
	
	/**
	 * @return integer[]
	 */
	public function getRecipientUserIDs() {
		$users = $this->getRecipientUsers();
		$userIDs = [];
		foreach ($users as $user) {
			$userIDs[] = $user->userID;
		}
		
		return $userIDs;
	}
	
	/**
	 * @return User[]
	 */
	public function getRecipientUsers() {
		$usernames = explode(',', str_replace(', ', ',', $this->recipientUsernames));
		$users = [];
		foreach ($usernames as $username) {
			$user = User::getUserByUsername($username);
			if ($user->userID) $users[] = $user;
		}
		
		return $users;
	}
}
