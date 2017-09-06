DROP TABLE IF EXISTS wcf1_user_notification_custom;
CREATE TABLE wcf1_user_notification_custom (
	notificationID     INT(10)      NOT NULL AUTO_INCREMENT,
	subject            VARCHAR(191),
	message            TEXT,
	url                TINYTEXT,
	userID             INT(10),
	username           VARCHAR(255) NOT NULL DEFAULT '',
	recipientUsernames TEXT,
	time               INT(10)      NOT NULL DEFAULT 0,
	isNotification     TINYINT(1)   NOT NULL DEFAULT 0,
	hasSucceeded       TINYINT(1)   NOT NULL DEFAULT 0,
	PRIMARY KEY (notificationID)
);

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

ALTER TABLE wcf1_user_notification_custom ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_notification_custom_queue ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_user_notification_custom_queue ADD FOREIGN KEY (notificationID) REFERENCES wcf1_user_notification_custom (notificationID) ON DELETE CASCADE;
