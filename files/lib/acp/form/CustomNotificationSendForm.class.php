<?php

namespace wcf\acp\form;

use wcf\data\user\notification\custom\CustomNotificationAction;
use wcf\data\user\User;
use wcf\system\exception\UserInputException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\WCF;
use wcf\util\MessageUtil;
use wcf\util\StringUtil;

/**
 * Class CustomNotificationSendForm
 *
 * @package wcf\acp\form
 * @property CustomNotificationAction $objectAction
 */
class CustomNotificationSendForm extends AbstractAcpForm {
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.user.wscConnect.notification.custom.canAdd'];
	
	/**
	 * @inheritDoc
	 */
	public $neededModules = ['MODULE_WSC_CONNECT'];
	
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.customnotifications.add';
	
	/**
	 * @var string
	 */
	public $subject = '';
	
	/**
	 * @var string
	 */
	public $message = '';
	
	/**
	 * @var string
	 */
	public $url = '';
	
	/**
	 * @var string
	 */
	public $recipients = '';
	
	/**
	 * @var boolean
	 */
	public $isNotification = 0;
	
	/**
	 * @var User[]
	 */
	public $recipientUsers = [];
	
	/**
	 * @var HtmlInputProcessor
	 */
	public $htmlInputProcessor;
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (!empty($_POST['subject'])) $this->subject = StringUtil::trim(MessageUtil::stripCrap($_POST['subject']));
		if (!empty($_POST['message'])) $this->message = StringUtil::trim(MessageUtil::stripCrap($_POST['message']));
		if (!empty($_POST['url'])) $this->url = StringUtil::trim(MessageUtil::stripCrap($_POST['url']));
		if (!empty($_POST['isNotification'])) $this->isNotification = 1;
		if (!empty($_POST['recipients'])) {
			$this->recipients = StringUtil::trim(MessageUtil::stripCrap($_POST['recipients']));
			
			$usernames = explode(',', str_replace(', ', ',', $this->recipients));
			foreach ($usernames as $username) {
				$user = User::getUserByUsername($username);
				if ($user->userID) $this->recipientUsers[] = User::getUserByUsername($username);
			}
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->subject)) throw new UserInputException('subject');
		if (empty($this->message)) throw new UserInputException('message');
		if (empty($this->url) && $this->isNotification) throw new UserInputException('url');
		if (empty($this->recipientUsers)) throw new UserInputException('recipients');
		
		$this->htmlInputProcessor = new HtmlInputProcessor();
		$this->htmlInputProcessor->process($this->message, 'de.mysterycode.wcf.wscConnect.notification.custom', 0);
		
		if ($this->htmlInputProcessor->appearsToBeEmpty()) {
			throw new UserInputException('message');
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();
		
		$this->objectAction = new CustomNotificationAction([], 'create', [
			'data' => array_merge($this->additionalFields, [
				'subject' => $this->subject,
				'url' => $this->url,
				'recipientUsernames' => $this->recipients,
				'userID' => WCF::getUser()->userID ?: null,
				'username' => WCF::getUser()->username,
				'time' => TIME_NOW,
				'isNotification' => $this->isNotification
			]),
			'htmlInputProcessor' => $this->htmlInputProcessor
		]);
		$notification = $this->objectAction->executeAction();
		
		$this->saveI18n($notification['returnValues'], CustomNotificationEditor::class);
		
		$this->reset();
	}
	
	/**
	 * @inheritDoc
	 */
	public function reset() {
		parent::reset();
		
		$this->subject = $this->message = $this->url = $this->recipients = '';
		$this->recipientUsers = [];
		$this->isNotification = 0;
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'subject' => $this->subject,
			'message' => $this->message,
			'url' => $this->url,
			'recipients' => $this->recipients,
			'isNotification' => $this->isNotification
		]);
	}
}
