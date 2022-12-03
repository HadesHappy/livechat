<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2020 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[config.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!file_exists('../../class/ssp.class.php')) die('ajax/[ssp.class.php] config.php not exist');
require_once '../../class/ssp.class.php';

$where = '';

// DB table to use
$table = JAKDB_PREFIX.'whatslog AS t1';
$table2 = ' LEFT JOIN '.JAKDB_PREFIX.'user AS t2 ON (t1.operatorid = t2.id)';

// Table's primary key
$primaryKey = 't1.id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 't1.id', 'dbjoin' => 'id', 'dt' => 0 ),
	array( 'db' => 't1.id', 'dbjoin' => 'id', 'dt' => 1, 'formatter' => function( $d, $row ) {
			return '<input type="checkbox" name="jak_delete_logs[]" class="highlight" value="'.$d.'">';
		} ),
	array( 'db' => 't1.whatsid', 'dbjoin' => 'whatsid', 'dt' => 2, 'formatter' => function( $d, $row ) {
			if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
			    include (APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
			} else {
			    include (APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
			}
			return sprintf($jkl["wl".$d], '<strong>'.(isset($row["username"]) ? $row["username"] : (isset($row["email"]) ? $row["email"] : $row["name"])).'</strong>');
		} ),
	array( 'db' => 't1.fromwhere', 'dbjoin' => 'fromwhere', 'dt' => 3, 'formatter' => function( $d, $row ) {
			return '<a href="'.$row["fromwhere"].'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="'.$row["fromwhere"].'"><i class="fa fa-link"></i></a> <a href="javascript:void(0)" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="'.$row["usragent"].'"><i class="fa fa-browser"></i></a> <a href="javascript:void(0)" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="'.$row["ip"].'"><i class="fal fa-globe"></i></a> <a href="javascript:void(0)" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="'.$row['country'].'"><i class="fa fa-flag"></i></a>';
		} ),
	array( 'db' => 't1.time', 'dbjoin' => 'time', 'dt' => 4, 'formatter' => function( $d, $row ) {
			return JAK_base::jakTimesince($d, JAK_DATEFORMAT, JAK_TIMEFORMAT);
		} ),
	array( 'db' => 't1.usragent', 'dbjoin' => 'usragent', 'dt' => 'usra' ),
	array( 'db' => 't1.ip', 'dbjoin' => 'ip', 'dt' => 'ip' ),
	array( 'db' => 't1.country', 'dbjoin' => 'country', 'dt' => 'cou' ),
	array( 'db' => 't2.username', 'dbjoin' => 'username', 'dt' => 'usr' ),
	array( 'db' => 't1.name', 'dbjoin' => 'name', 'dt' => 'name' )
);

die(json_encode(SSP::join( $_GET, $table, $table2, "", $primaryKey, $columns, $where, $where )));
?>