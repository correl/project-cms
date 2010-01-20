CREATE TABLE`acl` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`group` int(10) unsigned NOT NULL,
	`permission` int(10) unsigned NOT NULL,
	`access` set('YES','NO','NEVER') NOT NULL,
	PRIMARY KEY  (`id`)
);

CREATE TABLE `comments` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`post_id` int(10) unsigned NOT NULL,
	`name` varchar(80) DEFAULT NULL,
	`website` varchar(255) DEFAULT NULL,
	`ip_address` varchar(128) NOT NULL,
	`comment` text NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
);

CREATE TABLE `errors` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`caught` tinyint(3) unsigned NOT NULL DEFAULT 0,
	`severity` int(10) unsigned NOT NULL DEFAULT 0,
	`code` int(10) unsigned NOT NULL DEFAULT 0,
	`message` varchar(255) NOT NULL,
	`filename` varchar(255) NOT NULL,
	`line_number` int(10) unsigned NOT NULL,
	`trace` text DEFAULT NULL,
	`trace_string` text DEFAULT NULL,
	`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
);

CREATE TABLE `groups` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) default NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `pages` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`page_name` varchar(80) NOT NULL,
	`post_id` int(10) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
);

CREATE TABLE `permissions` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(80) NOT NULL,
	PRIMARY KEY  (`id`,`name`(1))
);

CREATE TABLE `posts` (
	`post_id` int(10) unsigned NOT NULL auto_increment,
	`user_id` int(10) unsigned NOT NULL DEFAULT 0,
	`project_id` int(10) unsigned NOT NULL DEFAULT 0,
	`post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`post_title` varchar(80) NOT NULL,
	`post_text` int(10) NOT NULL DEFAULT 0,
	`post_additional_text` int(10) NOT NULL DEFAULT 0,
	PRIMARY KEY (`post_id`)
);

CREATE TABLE `projects` (
	`project_id` int(10) unsigned NOT NULL auto_increment,
	`project_name` varchar(80) NOT NULL,
	`project_short_name` varchar(40) NOT NULL UNIQUE,
	`project_main_page` int(10) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`project_id`)
);

CREATE TABLE `text` (
	`text_id` int(10) unsigned NOT NULL auto_increment,
	`text` text NOT NULL,
	PRIMARY KEY (`text_id`)
);

CREATE TABLE `users` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`username` varchar(50) NOT NULL default '',
	`name` varchar(255) default NULL,
	`email` varchar(255) default '',
	`url` varchar(255) default '',
	`active` smallint(1) NOT NULL default '0',
	`password` varchar(40) NOT NULL default '',
	`force_pass_change` int( 1 ) NOT NULL default '0',
	PRIMARY KEY  (`id`)
);

CREATE TABLE `user_groups` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`user` int(10) unsigned NOT NULL,
	`group` int(10) unsigned NOT NULL,
	PRIMARY KEY  (`id`)
);
