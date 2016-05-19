SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; SET time_zone = "+00:00";DROP TABLE IF EXISTS `sulata_testimonials`;CREATE TABLE `sulata_testimonials` (`testimonial__ID` int(11) NOT NULL,`testimonial__Name` varchar(25) NOT NULL COMMENT '|s',`testimonial__Designation_and_Company` varchar(100) NOT NULL COMMENT '|s',`testimonial__Location` varchar(100) NOT NULL COMMENT '|s',`testimonial__Date` date NOT NULL COMMENT '|s',`testimonial__Status` enum('Active','Inactive') NOT NULL COMMENT '|s',`testimonial__Last_Action_On` datetime NOT NULL,`testimonial__Last_Action_By` varchar(64) NOT NULL,`testimonial__dbState` varchar(10) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;DROP TABLE IF EXISTS `sulata_blank`; CREATE TABLE `sulata_blank`( `__ID` int(11) NOT NULL, `__Last_Action_On` datetime NOT NULL, `__Last_Action_By` varchar(64) NOT NULL, `__dbState` varchar(10) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_faqs`; CREATE TABLE `sulata_faqs` ( `faq__ID` int(11) NOT NULL, `faq__Question` varchar(255) NOT NULL COMMENT '|s', `faq__Answer` text NOT NULL, `faq__Sequence` double NOT NULL COMMENT '|s',`faq__Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active' COMMENT '|s', `faq__Last_Action_On` datetime NOT NULL, `faq__Last_Action_By` varchar(64) NOT NULL, `faq__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_headers`; CREATE TABLE `sulata_headers` ( `header__ID` int(11) NOT NULL, `header__Title` varchar(64) NOT NULL COMMENT '|s', `header__Picture` varchar(128) NOT NULL, `header__Last_Action_On` datetime NOT NULL, `header__Last_Action_By` varchar(64) NOT NULL, `header__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_media_categories`; CREATE TABLE `sulata_media_categories` ( `mediacat__ID` int(11) NOT NULL, `mediacat__Name` varchar(64) NOT NULL COMMENT '|s', `mediacat__Picture` varchar(128) DEFAULT NULL, `mediacat__Description` text, `mediacat__Thumbnail_Width` int(11) NOT NULL, `mediacat__Thumbnail_Height` int(11) NOT NULL, `mediacat__Image_Width` int(11) NOT NULL, `mediacat__Image_Height` int(11) NOT NULL, `mediacat__Sequence` double NOT NULL COMMENT '|s', `mediacat__Last_Action_On` datetime NOT NULL, `mediacat__Last_Action_By` varchar(64) NOT NULL, `mediacat__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_media_files`; CREATE TABLE `sulata_media_files` ( `mediafile__ID` int(11) NOT NULL, `mediafile__Category` varchar(128) NOT NULL COMMENT '|s|mediacat__Name,mediacat__Name', `mediafile__Title` varchar(128) NOT NULL COMMENT '|s', `mediafile__Picture` varchar(128) NOT NULL COMMENT '|s|mediacat__ID,mediacat__Name', `mediafile__Short_Description` text, `mediafile__Long_Description` text, `mediafile__Sequence` double NOT NULL COMMENT '|s', `mediafile__Date` date NOT NULL COMMENT '|s', `mediafile__Last_Action_On` datetime NOT NULL, `mediafile__Last_Action_By` varchar(64) NOT NULL, `mediafile__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_pages`; CREATE TABLE `sulata_pages` ( `page__ID` int(11) NOT NULL, `page__Name` varchar(64) NOT NULL COMMENT '|s', `page__Heading` varchar(128) NOT NULL, `page__Permalink` varchar(64) NOT NULL, `page__Title` varchar(70) NOT NULL, `page__Keyword` varchar(255) NOT NULL, `page__Description` varchar(155) NOT NULL, `page__Header` varchar(64) NOT NULL COMMENT '|s|header__Title,header__Title', `page__Short_Text` text, `page__Long_Text` text NOT NULL, `page__Sequence` double NOT NULL COMMENT '|s', `page__Last_Action_On` datetime NOT NULL, `page__Last_Action_By` varchar(64) NOT NULL, `page__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8; DROP TABLE IF EXISTS `sulata_settings`; CREATE TABLE `sulata_settings` ( `setting__ID` int(11) NOT NULL, `setting__Setting` varchar(64) NOT NULL COMMENT '|s', `setting__Key` varchar(64) NOT NULL, `setting__Value` varchar(256) NOT NULL COMMENT '|s', `setting__Type` enum('Private','Public') NOT NULL COMMENT '|s', `setting__Last_Action_On` datetime NOT NULL, `setting__Last_Action_By` varchar(64) NOT NULL, `setting__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8; INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(1, 'Site Name', 'site_name', 'Rapid CMS', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(2, 'Site Tagline', 'site_tagline', 'BackOffice', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(3, 'Page Size', 'page_size', '2', 'Public', '2016-02-01 18:47:31', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(4, 'Time Zone', 'timezone', 'ASIA/KARACHI', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(5, 'Date Format', 'date_format', 'mm-dd-yy', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(6, 'Allowed File Formats', 'allowed_file_formats', 'doc,xls,docx,xlsx,ppt,pptx,pdf,gif,jpg,jpeg,png', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(7, 'Allowed Image Formats', 'allowed_image_formats', 'gif,jpg,jpeg,png', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(8, 'Allowed Attachment Formats', 'allowed_attachment_formats', 'doc,xls,docx,xlsx,ppt,pptx,pdf,gif,jpg,jpeg,png', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(10, 'Site Email', 'site_email', 'tahir@sulata.com.pk', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(11, 'Site URL', 'site_url', 'http://www.sulata.com.pk', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(12, 'Employee Image Height', 'employee_image_height', '150', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(13, 'Employee Image Width', 'employee_image_width', '100', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(14, 'Default Meta Title', 'default_meta_title', '-', 'Public', '2013-12-08 17:36:34', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(15, 'Default Meta Description', 'default_meta_description', '-', 'Public', '2013-12-09 09:45:02', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(16, 'Default Meta Keywords', 'default_meta_keywords', '-', 'Public', '2013-12-08 17:36:27', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(17, 'Default Theme', 'default_theme', 'default', 'Private', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(18, 'Header Width', 'header_width', '950', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(19, 'Header Height', 'header_height', '130', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(20, 'Media Category Width', 'media_category_width', '320', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(21, 'Media Category Height', 'media_category_height', '240', 'Public', '2016-02-25 00:00:00', '2016-02-25 00:00:00', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(22, 'Google Login Enable/Disable (1/0)', 'google_login', '0', 'Private', '2014-10-22 11:51:05', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(23, 'Site Footer', 'site_footer', 'Developed by Sulata iSoft.', 'Public', '2014-11-01 16:25:31', 'Installer', 'Live'); INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(24, 'Site Footer Link', 'site_footer_link', 'http://www.sulata.com.pk', 'Public', '2014-11-01 16:25:51', 'Installer', 'Live');INSERT INTO `sulata_settings` (`setting__ID`, `setting__Setting`, `setting__Key`, `setting__Value`, `setting__Type`, `setting__Last_Action_On`, `setting__Last_Action_By`, `setting__dbState`) VALUES(25, 'Multi Login Enable/Disable (1/0)', 'multi_login', '1', 'Public', '2016-05-11 00:00:00', 'Tahir Ata Barry', 'Live'); DROP TABLE IF EXISTS `sulata_users`; CREATE TABLE `sulata_users` ( `user__ID` int(11) NOT NULL, `user__Name` varchar(32) NOT NULL COMMENT '|s', `user__Phone` varchar(32) DEFAULT NULL COMMENT '|s', `user__Email` varchar(64) NOT NULL COMMENT '|s', `user__Password` varchar(64) NOT NULL,`user__Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active' COMMENT '|s', `user__Picture` varchar(128) DEFAULT NULL, `user__Type` varchar(64) NOT NULL COMMENT '|s|usertype__Type,usertype__Type', `user__Notes` text, `user__Theme` varchar(24) NOT NULL DEFAULT 'default', `user__Last_Action_On` datetime NOT NULL, `user__Last_Action_By` varchar(64) NOT NULL, `user__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8; INSERT INTO `sulata_users` (`user__ID`, `user__Name`, `user__Phone`, `user__Email`, `user__Password`, `user__Status`, `user__Picture`, `user__Type`, `user__Notes`, `user__Theme`, `user__Last_Action_On`, `user__Last_Action_By`, `user__dbState`) VALUES(1, 'Tahir  Ata Barry', '', 'tahir@sulata.com.pk', 'ZEdGb2FYST0=', 'Active', 'cable-guy-51ad9b1c6fc4b-51adb20308d92.jpg', 'Admin', '', 'default', '2014-10-25 16:53:53', 'Installer', 'Live'); DROP TABLE IF EXISTS `sulata_user_types`; CREATE TABLE `sulata_user_types` ( `usertype__ID` int(11) NOT NULL, `usertype__Type` varchar(64) NOT NULL COMMENT '|s', `usertype__Last_Action_On` datetime NOT NULL, `usertype__Last_Action_By` varchar(64) NOT NULL, `usertype__dbState` varchar(10) NOT NULL ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8; INSERT INTO `sulata_user_types` (`usertype__ID`, `usertype__Type`, `usertype__Last_Action_On`, `usertype__Last_Action_By`, `usertype__dbState`) VALUES(1, 'Admin', '2016-02-25 00:00:00', 'Installer', 'Live'); INSERT INTO `sulata_user_types` (`usertype__ID`, `usertype__Type`, `usertype__Last_Action_On`, `usertype__Last_Action_By`, `usertype__dbState`) VALUES(2, 'User', '2016-02-25 00:00:00', 'Installer', 'Live'); ALTER TABLE `sulata_blank` ADD PRIMARY KEY (`__ID`); ALTER TABLE `sulata_faqs` ADD PRIMARY KEY (`faq__ID`), ADD UNIQUE KEY `faq__Question` (`faq__Question`); ALTER TABLE `sulata_headers` ADD PRIMARY KEY (`header__ID`), ADD UNIQUE KEY `header__Title` (`header__Title`); ALTER TABLE `sulata_media_categories` ADD PRIMARY KEY (`mediacat__ID`), ADD UNIQUE KEY `mediacat__Name` (`mediacat__Name`);ALTER TABLE `sulata_media_files` ADD PRIMARY KEY (`mediafile__ID`), ADD UNIQUE KEY `mediafile__File` (`mediafile__Picture`);ALTER TABLE `sulata_pages` ADD PRIMARY KEY (`page__ID`), ADD UNIQUE KEY `page__Name` (`page__Name`); ALTER TABLE `sulata_settings` ADD PRIMARY KEY (`setting__ID`), ADD UNIQUE KEY `setting__Key` (`setting__Key`), ADD UNIQUE KEY `setting__Setting` (`setting__Setting`); ALTER TABLE `sulata_users` ADD PRIMARY KEY (`user__ID`), ADD UNIQUE KEY `employee__Email` (`user__Email`); ALTER TABLE `sulata_user_types` ADD PRIMARY KEY (`usertype__ID`), ADD UNIQUE KEY `usertype__Type` (`usertype__Type`); ALTER TABLE `sulata_blank` MODIFY `__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_faqs` MODIFY `faq__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_headers` MODIFY `header__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_media_categories` MODIFY `mediacat__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_media_files` MODIFY `mediafile__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_pages` MODIFY `page__ID` int(11) NOT NULL AUTO_INCREMENT; ALTER TABLE `sulata_settings` MODIFY `setting__ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25; ALTER TABLE `sulata_users` MODIFY `user__ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;ALTER TABLE `sulata_user_types` MODIFY `usertype__ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;ALTER TABLE `sulata_testimonials` ADD PRIMARY KEY (`testimonial__ID`), ADD UNIQUE KEY `testimonial__Designation_and_Company` (`testimonial__Designation_and_Company`);ALTER TABLE `sulata_testimonials` MODIFY `testimonial__ID` int(11) NOT NULL AUTO_INCREMENT;
