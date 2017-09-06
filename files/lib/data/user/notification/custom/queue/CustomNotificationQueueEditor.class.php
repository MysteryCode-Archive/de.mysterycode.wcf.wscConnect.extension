<?php

namespace wcf\data\user\notification\custom\queue;

use wcf\data\DatabaseObjectEditor;

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
	protected static $baseClass = CustomNotificationQueue::class;
}
