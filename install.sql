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
	PRIMARY KEY (notificationID)
);

ALTER TABLE wcf1_user_notification_custom ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
