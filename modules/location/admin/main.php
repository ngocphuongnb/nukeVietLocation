<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

///////////// SEARCH /////////////
$s['keyword']		= $nv_Request->get_string( 'keyword', 'get', '' );
$s['parent_id']		= $nv_Request->get_int( 'parent_id', 'get', 0 );
$s['location_type']	= $nv_Request->get_int( 'location_type', 'get', 0 );

$_locations = array( 0 => array('location_id' => 0, 'location_name' => '---', 'parent_id' => 0 ));
if( $locationList = getLocations() )
{
	$_locations = array_merge($_locations, $locationList);
}
foreach( $_locations as $_loc )
{
	( $s['parent_id'] == $_loc['location_id'] ) ? $_loc['slt'] = ' selected="selected"' : $_loc['slt'] = '';
	$xtpl->assign('LOCATION', $_loc );
	$xtpl->parse('main.search.locations');
}

foreach( $locationType as $_locTypeID => $_locTypeName )
{
	$_locType = array('locID' => $_locTypeID, 'locName' => $_locTypeName );
	( $s['location_type'] == $_locTypeID ) ? $_locType['slt'] = ' selected="selected"' : $_locType['slt'] = '';
	$xtpl->assign('LOCTYPE', $_locType );
	$xtpl->parse('main.search.location_type');
}
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'S', $s );
$xtpl->parse( 'main.search' );
//////////////////////////////////

$page_title = $lang_module['list'];
$array = array();

$per_page = 10;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$_s = array();

if( !empty($s['keyword'] ) )
{
	$_s[] = "location_name like '%" . $db->dblikeescape( $s['keyword'] ) . "%'";
}
if( !empty($s['location_type'] ) )
{
	$_s[] = 'location_type = ' . $s['location_type'];
}

if( !empty($s['parent_id'] ) )
{
	$_s[] = 'parent_id = ' . $s['parent_id'];
}
$where = '';
if( !empty($_s) ) $where = implode(' ', $_s);

$db->sqlreset()
->select( 'COUNT(*)' )
->from( NV_PREFIXLANG . '_' . $module_data )
->where( $where );

$all_page = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )
	->order( 'location_id' )
	->limit( $per_page )
	->offset( $page );
$result = $db->query( $db->sql() );

while( $_r = $result->fetch( 2 ) )
{
	$_rows[$_r['location_id']] = $_r;			
}

$rows = array();
	
if(!empty($_rows) )
{
	foreach($_rows as $_loc)
	{
		$rows[$_loc['location_id']] = $_loc;
	}
}
$allLocations = $rows;

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;parent_id=' . $s['parent_id'] . '&amp;location_type=' . $s['location_type'];

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

if( !empty($allLocations) )
foreach ( $allLocations as $row )
{
	$row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;location_id=' . $row['location_id'];

	if( $row['parent_id'] > 0 && array_key_exists($row['parent_id'],$allLocations)  )
	{
		$row['parent_location'] = $allLocations[$row['parent_id']]['location_name'];
		$row['parent_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main&parent_id=' . $row['parent_id'];
	}
	$row['location_type']	= $locationType[$row['location_type']];
	
	$row['show_child_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main&parent_id=' . $row['location_id'];
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>