<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */
 
 
$locationType = array(
						$lang_module['location_type_0'],
						$lang_module['location_type_1'],
						$lang_module['location_type_2'],
						$lang_module['location_type_3'],
						$lang_module['location_type_4'],
						$lang_module['location_type_5'],
						$lang_module['location_type_6'],
						$lang_module['location_type_7']
					);
$allLocations = getLocations();
					
function getLocations($location_id = 0)
{
	global $db, $module_data;
	
	if( $location_id > 0 )
	{
		$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE location_id=' . $location_id;
		$row = $db->query( $sql )->fetch();
		if(empty( $row ) ) return false;
		else return $row;
	}
	
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY location_id ASC';
	$_rows = $db->query( $sql )->fetchAll();
	$num = sizeof( $_rows );
	
	if( $num < 1 ) return false;
	else
	{
		$rows = array();
		foreach($_rows as $_loc)
		{
			$rows[$_loc['location_id']] = $_loc;
		}
		return $rows;
	}
}

function remove_location($location_id = 0)
{
	if( $location_id > 0 )
	{
		global $db, $module_data, $module_name, $admin_info;
		
		$sql = 'SELECT location_id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE location_id=' . $location_id;
		$_location_id = $db->query( $sql )->fetchColumn();
		
		if( empty( $_location_id ) ) return false;
		
		$sql = 'SELECT location_id, location_name FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE parent_id=' . $location_id;
		$_rows = $db->query( $sql )->fetchAll();
		if( sizeof( $_rows ) > 0 )
		{
			$_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET parent_id = 0 WHERE parent_id =' . $location_id;
			$sth = $db->prepare( $_sql );
			$sth->execute();
		}
		if(1)
		{	
			$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE location_id = ' . $location_id;
			if( $db->exec( $sql ) ) return array('status' => 'ok');
			else return false;
		}
		else return array('status' => 'hasChild', 'childs' => $_rows);
	}
	return false;
}

function remove_list($locationIDs = '')
{
	if( $locationIDs != '' )
	{
		global $db, $module_data, $module_name, $admin_info;
		
		$_IDs = explode(',', $locationIDs);
		
		$locationIDs = array();
		
		foreach( $_IDs as $_id )
		{
			if( !empty($_id) ) $locationIDs[] = intval($_id);
		}
		$locationIDs = implode(',', $locationIDs);
		
		$sql = 'SELECT location_id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE location_id IN (' . $locationIDs . ')';

		$_location_IDs = $db->query( $sql )->fetchColumn();
		
		if( empty( $_location_IDs ) ) return false;
		
		$sql = 'SELECT location_id, location_name FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE parent_id IN (' . $locationIDs . ')';
		$_rows = $db->query( $sql )->fetchAll();
		if( sizeof( $_rows ) > 0 )
		{
			$_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET parent_id = 0 WHERE parent_id IN (' . $locationIDs . ')';
			$sth = $db->prepare( $_sql );
			$sth->execute();
		}
		if(1)
		{	
			$sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE location_id IN (' . $locationIDs . ')';
			if( $db->exec( $sql ) ) return array('status' => 'ok');
			else return false;
		}
		else return array('status' => 'hasChild', 'childs' => $_rows);
	}
	return false;
}

function n($str)
{
	echo '<pre>';
	print_r($str);
	echo '</pre>';
	die();
}

?>