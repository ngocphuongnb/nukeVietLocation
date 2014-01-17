<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$allow_func = array( 'main', 'content', 'remove_location', 'remove_list' );

define( 'NV_IS_FILE_ADMIN', true );

?>