<?php

namespace wcf\acp\form;

use wcf\data\user\notification\custom\CustomNotificationAction;
use wcf\data\user\notification\custom\CustomNotificationEditor;
use wcf\data\user\User;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\MessageUtil;
use wcf\util\StringUtil;

/**
 * Class CustomNotificationSendForm
 *
 * @package wcf\acp\form
 * @property CustomNotificationAction $objectAction
 */
//TODO tornado
//class CustomNotificationSendForm extends AbstractAcpForm {
class CustomNotificationSendForm extends AbstractForm {
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
	 * @var User[]
	 */
	public $recipientUsers = [];
	
	/**
	 * @var string
	 * @deprecated
	 * TODO tornado
	 */
	public $action = 'add';
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (!empty($_POST['subject'])) $this->subject = StringUtil::trim(MessageUtil::stripCrap($_POST['subject']));
		if (!empty($_POST['message'])) $this->message = StringUtil::trim(MessageUtil::stripCrap($_POST['message']));
		if (!empty($_POST['url'])) $this->url = StringUtil::trim(MessageUtil::stripCrap($_POST['url']));
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
		if (empty($this->url)) throw new UserInputException('url');
		if (empty($this->recipientUsers)) throw new UserInputException('recipients');
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();
		
		$this->objectAction = new CustomNotificationAction([], 'create', [
			'data' => array_merge($this->additionalFields, [
				'subject' => $this->subject,
				'message' => $this->message,
				'url' => $this->url,
				'recipientUsernames' => $this->recipients,
				'userID' => WCF::getUser()->userID ?: null,
				'username' => WCF::getUser()->username,
				'time' => TIME_NOW
			])
		]);
		$notification = $this->objectAction->executeAction();
		
		//TODO tornado
		//$this->saveI18n($notification['returnValues'], CustomNotificationEditor::class);
		
		$this->reset();
	}
	
	/**
	 * @inheritDoc
	 */
	public function reset() {
		//TODO tornado
		//parent::reset();
		$this->saved();
		// show success message
		WCF::getTPL()->assign('success', true);
		
		$this->subject = $this->message = $this->url = $this->recipients = '';
		$this->recipientUsers = [];
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
			'recipients' => $this->recipients
		]);
	}
}
