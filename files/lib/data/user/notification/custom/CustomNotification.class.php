<?php

namespace wcf\data\user\notification\custom;

use wcf\data\user\User;
use wcf\data\DatabaseObject;
use wcf\system\html\output\HtmlOutputProcessor;
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
	 * @var string[]
	 */
	public const SUPPORTED_VARIABLES = ['username', 'userID', 'email'];
	
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
		$message = WCF::getLanguage()->get($this->message);
		
		$htmlOutputProcessor = new HtmlOutputProcessor();
		$htmlOutputProcessor->setOutputType('text/html');
		$htmlOutputProcessor->process($message, 'de.mysterycode.wcf.wscConnect.notification.custom', $this->notificationID);
		
		$message = preg_replace_callback('/\{\$(' . implode('|', self::SUPPORTED_VARIABLES) . ')\$\}/', function ($match) {
			return $this->replaceVariable($match);
		}, $htmlOutputProcessor->getHtml());
		
		return $message;
	}
	
	/**
	 * @param string[] $matches
	 * @return string
	 */
	protected function replaceVariable($matches = []) {
		if ($matches[1] == 'username') {
			return '<a href="' . WCF::getUser()->getLink() . '" class="userLink" data-user-id="' . WCF::getUser()->userID . '">'. WCF::getUser()->username . '</a>';
		}
		else {
			return WCF::getUser()->{$matches[1]};
		}
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
