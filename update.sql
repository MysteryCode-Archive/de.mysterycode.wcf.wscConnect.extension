ALTER TABLE wcf1_user_notification_custom ADD COLUMN isNotification     TINYINT(1)   NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user_notification_custom ADD COLUMN hasSucceeded     TINYINT(1)   NOT NULL DEFAULT 0;

DROP TABLE IF EXISTS wcf1_user_notification_custom_queue;
CREATE TABLE wcf1_user_notification_custom_queue (
	itemID         INT(10)    NOT NULL AUTO_INCREMENT,
	notificationID INT(10),
	userID         INT(10),
	errored        INT(10)    NOT NULL DEFAULT 0,
	error          TEXT,
	isNotification TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (itemID)
);

ALTER TABLE wcf1_user_notification_custom_queue ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_notification_custom_queue ADD FOREIGN KEY (notificationID) REFERENCES wcf1_user_notification_custom (notificationID) ON DELETE CASCADE;

UPDATE wcf1_user_notification_custom SET isNotification = 1;
UPDATE wcf1_user_notification_custom SET hasSucceeded = 1;
