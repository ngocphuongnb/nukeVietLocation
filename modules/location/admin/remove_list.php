<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */
 
 
$location_IDs = $nv_Request->get_string( 'listIDs', 'post,get', '' );

$return = json_encode(array('status' => 'not'));
if( $location_IDs )
{
	$remove_preparing = remove_list($location_IDs);
	
	if( $remove_preparing['status'] == 'ok' )
	{
		echo json_encode($remove_preparing);
		die();
	}
	elseif( $remove_preparing['status'] == 'hasChild' )
	{
		$remove_preparing['location_id'] = $location_id;
		echo json_encode($remove_preparing);
		die();
	}
}

echo $return; die();

?>