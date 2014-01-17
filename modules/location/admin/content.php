<?php

/**
 * @Project NUKEVIET iFinance 1.x
 * @Author Nguyen Ngoc Phuong (nguyenngocphuongnb@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/01/2014 10:30
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$location_id = $nv_Request->get_int( 'location_id', 'post,get', 0 );

if( $location_id )
{
	if( !$row = getLocations($location_id) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['edit'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;location_id=' . $location_id;
}
else
{
	$page_title = $lang_module['add'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$error = '';

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$row['location_name']	= $nv_Request->get_title( 'location_name', 'post', '', 1 );
	$row['latitude']		= $nv_Request->get_string( 'latitude', 'post', '' );
	$row['longitude']		= $nv_Request->get_string( 'longitude', 'post', '' );
	$row['location_type']	= $nv_Request->get_int( 'location_type', 'post', 0 );
	$row['parent_id']		= $nv_Request->get_int( 'parent_id', 'post', 0 );

	$row['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );

	if( empty( $row['location_name'] ) )
	{
		$error = $lang_module['empty_location_name'];
	}
	else
	{
		$row['description'] = nv_editor_nl2br( $row['description'] );

		if( $location_id )
		{
			$_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET
				location_name	= :location_name,
				latitude		= :latitude,
				longitude 		= :longitude,
				location_type	= :location_type,
				description		= :description,
				parent_id 		= :parent_id
			 WHERE location_id =' . $location_id;
		}
		else
		{
			$_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
				location_name,
				latitude,
				longitude,
				location_type,
				description,
				parent_id
			) VALUES (
				:location_name, 
				:latitude, 
				:longitude, 
				:location_type, 
				:description,
				:parent_id
			)';
		}

		$sth = $db->prepare( $_sql );
		$sth->bindParam( ':location_name', $row['location_name'], PDO::PARAM_STR );
		$sth->bindParam( ':latitude', $row['latitude'], PDO::PARAM_STR );
		$sth->bindParam( ':longitude', $row['longitude'], PDO::PARAM_STR );
		$sth->bindParam( ':location_type', $row['location_type'], PDO::PARAM_INT );
		$sth->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ) );
		$sth->bindParam( ':parent_id', $row['parent_id'], PDO::PARAM_INT );
		$sth->execute();

		if( $sth->rowCount() )
		{
			if( $location_id )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit', 'LocationID: ' . $location_id , $admin_info['userid'] );
			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid'] );
			}

			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}
elseif( $location_id )
{
	$row['description'] = nv_editor_br2nl( $row['description'] );
}
else
{
	$row = array(
				'location_id'	=> 0,
				'parent_id'		=> 0,
				'description'	=> '',
				'latitude'		=> '21.02859066637007',
				'longitude'		=> '105.85381865501404',
				'location_type'	=> 0,
				'location_name'	=> ''
			);
				
}

if( ! empty( $row['description'] ) ) $row['description'] = nv_htmlspecialchars( $row['description'] );

if( defined( 'NV_EDITOR' ) ) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$row['description'] = nv_aleditor( 'description', '100%', '300px', $row['description'] );
}
else
{
	$row['description'] = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
}

if( ! empty( $row['image'] ) AND is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image'] ) )
{
	$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
}

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'DATA', $row );
$xtpl->assign( 'DESC', $row['description'] );

if( $error )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

//$locationList = getLocations();

$_locations = array( 0 => array('location_id' => 0, 'location_name' => '---', 'parent_id' => 0 ));
if( $locationList = getLocations() )
{
	$_locations = array_merge($_locations, $locationList);
}

foreach( $_locations as $_loc )
{
	( $row['parent_id'] == $_loc['location_id'] ) ? $_loc['slt'] = ' selected="selected"' : $_loc['slt'] = '';
	$xtpl->assign('LOCATION', $_loc );
	$xtpl->parse('main.locations');
}

foreach( $locationType as $_locTypeID => $_locTypeName )
{
	$_locType = array('locID' => $_locTypeID, 'locName' => $_locTypeName );
	( $row['location_type'] == $_locTypeID ) ? $_locType['slt'] = ' selected="selected"' : $_locType['slt'] = '';
	$xtpl->assign('LOCTYPE', $_locType );
	$xtpl->parse('main.location_type');
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>