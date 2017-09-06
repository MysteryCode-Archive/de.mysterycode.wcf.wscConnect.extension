<?php

namespace wcf\data\user\notification\custom\queue;

use wcf\data\DatabaseObjectEditor;
use wcf\data\user\notification\custom\CustomNotification;

/**
 * Class CustomNotificationQueueEditor
 *
 * @package wcf\data\user\notification\custom\queue
 *
 * @method CustomNotificationQueue $object
 * @method CustomNotificationQueue getDecoratedObject()
 * @mixin  CustomNotificationQueue
 */
class CustomNotificationQueueEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = CustomNotification::class;
}
