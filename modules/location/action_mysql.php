<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . ";";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
 location_id int(10) unsigned NOT NULL AUTO_INCREMENT,
 parent_id int(10) unsigned NOT NULL,
 description varchar(255) NOT NULL DEFAULT '',
 latitude varchar(15) NOT NULL DEFAULT '',
 longitude varchar(15) DEFAULT '',
 location_name varchar(255) DEFAULT '',
 location_type tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (location_id)
) ENGINE=MyISAM";

?>