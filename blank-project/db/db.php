<?php

/** Connection * */
$cn = new MongoClient('localhost');
/** select database * */
$db = $cn->selectDB($_POST['db']);

/** drop database * */
$db->drop();

/** create database * */
$db = $cn->selectDB($_POST['db']);

/** create sulata_testimonials indexes * */
$col = new MongoCollection($db, 'sulata_testimonials');
$col->ensureIndex(array('_id' => 1));

/** create sulata_testimonials indexes * */
$col = new MongoCollection($db, 'sulata_testimonials');
$col->ensureIndex(array('testimonial__Designation_and_Company' => 1), array('unique' => TRUE));

/** sulata_faqs indexes * */
$col = new MongoCollection($db, 'sulata_faqs');
$col->ensureIndex(array('_id' => 1));

/** sulata_faqs indexes * */
$col = new MongoCollection($db, 'sulata_faqs');
$col->ensureIndex(array('faq__Question' => 1), array('unique' => TRUE));

/** sulata_headers indexes * */
$col = new MongoCollection($db, 'sulata_headers');
$col->ensureIndex(array('_id' => 1));

/** sulata_headers indexes * */
$col = new MongoCollection($db, 'sulata_headers');
$col->ensureIndex(array('header__Title' => 1), array('unique' => TRUE));

/** sulata_media_categories indexes * */
$col = new MongoCollection($db, 'sulata_media_categories');
$col->ensureIndex(array('_id' => 1));

/** sulata_media_categories indexes * */
$col = new MongoCollection($db, 'sulata_media_categories');
$col->ensureIndex(array('mediacat__Name' => 1), array('unique' => TRUE));

/** sulata_media_files indexes * */
$col = new MongoCollection($db, 'sulata_media_files');
$col->ensureIndex(array('_id' => 1));

/** sulata_media_files indexes * */
$col = new MongoCollection($db, 'sulata_media_files');
$col->ensureIndex(array('mediafile__Category' => 1, 'mediafile__Title' => 1), array('unique' => TRUE));

/** sulata_pages indexes * */
$col = new MongoCollection($db, 'sulata_pages');
$col->ensureIndex(array('_id' => 1));

/** sulata_pages indexes * */
$col = new MongoCollection($db, 'sulata_pages');
$col->ensureIndex(array('page__Name' => 1), array('unique' => TRUE));

/** sulata_settings indexes * */
$col = new MongoCollection($db, 'sulata_settings');
$col->ensureIndex(array('_id' => 1));

/** sulata_settings indexes * */
$col = new MongoCollection($db, 'sulata_settings');
$col->ensureIndex(array('setting__Setting' => 1), array('unique' => TRUE));

/** sulata_settings indexes * */
$col = new MongoCollection($db, 'sulata_settings');
$col->ensureIndex(array('setting__Key' => 1), array('unique' => TRUE));

/** sulata_user_types indexes * */
$col = new MongoCollection($db, 'sulata_user_types');
$col->ensureIndex(array('_id' => 1));

/** sulata_user_types indexes * */
$col = new MongoCollection($db, 'sulata_user_types');
$col->ensureIndex(array('usertype__Type' => 1), array('unique' => TRUE));

/** sulata_users indexes * */
$col = new MongoCollection($db, 'sulata_users');
$col->ensureIndex(array('_id' => 1));

/** sulata_users indexes * */
$col = new MongoCollection($db, 'sulata_users');
$col->ensureIndex(array('user__Email' => 1), array('unique' => TRUE));

/** sulata_settings records * */
$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site Name", "setting__Key" => "site_name", "setting__Key_slug" => "site-name", "setting__Value" => "Rapid Mongo CMS", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);


$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site Tagline", "setting__Key" => "site_tagline", "setting__Key_slug" => "site-tagline", "setting__Value" => "BackOffice", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Page Size", "setting__Key" => "page_size", "setting__Key_slug" => "page-size", "setting__Value" => "20", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Time Zone", "setting__Key" => "timezone", "setting__Key_slug" => "timezone", "setting__Value" => "ASIA/KARACHI", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Date Format", "setting__Key" => "date_format", "setting__Key_slug" => "date-format", "setting__Value" => "mm-dd-yy", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Allowed File Formats", "setting__Key" => "allowed_file_formats", "setting__Key_slug" => "allowed-file-formats", "setting__Value" => "doc,xls,docx,xlsx,ppt,pptx,pdf,gif,jpg,jpeg,png", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);


$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Allowed Image Formats", "setting__Key" => "allowed_image_formats", "setting__Key_slug" => "allowed-image-formats", "setting__Value" => "gif,jpg,jpeg,png", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Allowed Attachment Formats", "setting__Key" => "allowed_attachment_formats", "setting__Key_slug" => "allowed-attachment-formats", "setting__Value" => "doc,xls,docx,xlsx,ppt,pptx,pdf,gif,jpg,jpeg,png", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site Email", "setting__Key" => "site_email", "setting__Key_slug" => "site-email", "setting__Value" => "mongo@sulata.com.pk", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site URL", "setting__Key" => "site_url", "setting__Key_slug" => "site-url", "setting__Value" => "http://www.sulata.com.pk", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Employee Image Height", "setting__Key" => "employee_image_height", "setting__Key_slug" => "employee-image-height", "setting__Value" => "150", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Employee Image Width", "setting__Key" => "employee_image_width", "setting__Key_slug" => "employee-image-width", "setting__Value" => "100", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Default Meta Title", "setting__Key" => "default_meta_title", "setting__Key_slug" => "default-meta-title", "setting__Value" => "-", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Default Meta Description", "setting__Key" => "default_meta_description", "setting__Key_slug" => "default-meta-description", "setting__Value" => "-", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Default Meta Keywords", "setting__Key" => "default_meta_keywords", "setting__Key_slug" => "default-meta-keywords", "setting__Value" => "-", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Default Theme", "setting__Key" => "default_theme", "setting__Key_slug" => "default-theme", "setting__Value" => "default", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Header Width", "setting__Key" => "header_width", "setting__Key_slug" => "header-width", "setting__Value" => "950", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Header Height", "setting__Key" => "header_height", "setting__Key_slug" => "header-height", "setting__Value" => "950", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Media Category Width", "setting__Key" => "media_category_width", "setting__Key_slug" => "media-category-width", "setting__Value" => "320", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Media Category Height", "setting__Key" => "media_category_height", "setting__Key_slug" => "media-category-height", "setting__Value" => "240", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Google Login Enable/Disable (1/0)", "setting__Key" => "google_login", "setting__Key_slug" => "google-login", "setting__Value" => "0", "setting__Type" => "Private", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site Footer", "setting__Key" => "site_footer", "setting__Key_slug" => "site-footer", "setting__Value" => "Developed by Sulata iSoft.", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Site Footer Link", "setting__Key" => "site_footer_link", "setting__Key_slug" => "site-footer-link", "setting__Value" => "http://www.sulata.com.pk", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Multi Login Enable/Disable (1/0)", "setting__Key" => "multi_login", "setting__Key_slug" => "multi-login", "setting__Value" => "1", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_settings');
$data = array("setting__Setting" => "Table View or Card View (table/card)", "setting__Key" => "table_or_card", "setting__Key_slug" => "table-or-card", "setting__Value" => "card", "setting__Type" => "Public", "setting__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "setting__Last_Action_By" => "Installer", "setting__dbState" => "Live");
$col->insert($data);

/** sulata_user_types records * */
$col = new MongoCollection($db, 'sulata_user_types');
$data = array("usertype__Type" => "Admin", "usertype__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "usertype__Last_Action_By" => "Installer", "usertype__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_user_types');
$data = array("usertype__Type" => "User", "usertype__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "usertype__Last_Action_By" => "Installer", "usertype__dbState" => "Live");
$col->insert($data);

$col = new MongoCollection($db, 'sulata_users');
$data = array("user__Name" => "Mongo", "user__Phone" => "", "user__Email" => "mongo@sulata.com.pk", "user__Email_slug" => "mongo-sulata-com-pk", "user__Password" => "Ylc5dVoyOHhNak09", "user__Status" => "Active", "user__Type" => "Admin", "user__Picture" => "mongo-user.png", "user__Notes" => "", "user__Theme" => "default", "user__Last_Action_On" => new MongoDate(strtotime(date('Y-m-d H:i:s'))), "user__Last_Action_By" => "Installer", "user__dbState" => "Live");
$col->insert($data);
