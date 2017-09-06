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
 * @property-read boolean $isNotification
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
	const SUPPORTED_VARIABLES = ['username', 'userID', 'email'];
	
	/**
	 * @return string
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->subject);
	}
	
	/**
	 * @var integer
	 */
	const MAX_API_REQUESTS = 20;
	
	/**
	 * @param boolean $skipReplace prevents replacing variables by it's values
	 * @param string  $outputType
	 * @return string
	 */
	public function getMessage($skipReplace = false, $outputType = 'text/html') {
		if (!$skipReplace) {
			return $this->getPersonalizedMessage(WCF::getUser(), $outputType);
		} else {
			$htmlOutputProcessor = new HtmlOutputProcessor();
			$htmlOutputProcessor->setOutputType($outputType);
			$htmlOutputProcessor->process(WCF::getLanguage()->get($this->message), 'de.mysterycode.wcf.wscConnect.notification.custom', $this->notificationID);
			return $htmlOutputProcessor->getHtml();
		}
	}
	
	public function getPersonalizedMessage(User $user, $outputType = 'text/html') {
		$message = WCF::getLanguage()->get($this->message);
		
		$htmlOutputProcessor = new HtmlOutputProcessor();
		$htmlOutputProcessor->setOutputType($outputType);
		$htmlOutputProcessor->process($message, 'de.mysterycode.wcf.wscConnect.notification.custom', $this->notificationID);
		$message = $htmlOutputProcessor->getHtml();
		
		return preg_replace_callback('/\{\$(' . implode('|', self::SUPPORTED_VARIABLES) . ')\$\}/', function ($match) use ($user, $outputType) {
			return $this->replaceVariable($match, $user, $outputType);
		}, $message);
	}
	
	/**
	 * @param string[] $matches
	 * @return string
	 */
	protected function replaceVariable($matches = [], User $user, $outputType) {
		if ($matches[1] == 'username') {
			if ($outputType == 'text/plain')
				return $user->username;
			else
				return '<a href="' . $user->getLink() . '" class="userLink" data-user-id="' . $user->userID . '">'. $user->username . '</a>';
		}
		else {
			return $user->{$matches[1]};
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
