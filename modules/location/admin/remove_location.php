<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */
 
 
$location_id = $nv_Request->get_int( 'location_id', 'post,get', 0 );

$return = json_encode(array('status' => 'not'));
if( $location_id )
{
	$remove_preparing = remove_location($location_id);
	
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