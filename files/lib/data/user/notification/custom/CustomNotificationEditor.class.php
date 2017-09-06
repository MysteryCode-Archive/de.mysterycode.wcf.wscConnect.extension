<?php

namespace wcf\data\user\notification\custom;

use wcf\data\DatabaseObjectEditor;

/**
 * Class CustomNotificationEditor
 *
 * @package wcf\data\user\notification\custom
 *
 * @mixin CustomNotification
 * @method  CustomNotification getDecoratedObject()
 */
class CustomNotificationEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = CustomNotification::class;
}
