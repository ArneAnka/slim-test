CREATE TABLE `notes` (
	`note_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_id`	TEXT,
	`text`	TEXT,
	`ip_adress`	TEXT,
	`soft_delete` INTEGER DEFAULT NULL,
	`created_at`	TEXT,
	`updated_at`	TEXT
);

CREATE TABLE `user` (
	`user_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_namn`	TEXT,
	`user_password_hash` TEXT,
	`user_account_type` INTEGER,
	`user_failed_logins` TEXT,
	`user_last_failed_login` TEXT,
	`sign_in_ip_adress`	TEXT,
	`created_at`	TEXT,
	`updated_at`	TEXT
);