<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[config.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!file_exists('../../class/ssp.class.php')) die('ajax/[ssp.class.php] config.php not exist');
require_once '../../class/ssp.class.php';

// Check if user is logged in
$jakuserlogin = new JAK_userlogin();
$jakuserrow = $jakuserlogin->jakChecklogged();
$jakuser = new JAK_user($jakuserrow);

$where = '';
if (!jak_get_access("leads_all", $jakuser->getVar("permissions"), $jakuser->jakSuperadminaccess($jakuser->getVar("id")))) {

	if (is_numeric($jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

		$where = "t1.operatorid = ".$jakuser->getVar("id")." OR department = ".$jakuser->getVar("departments");

	} elseif (!((boolean)$jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

		$where = "t1.operatorid = ".$jakuser->getVar("id")." OR department IN (".$jakuser->getVar("departments").")";

	} else {
		$where = "t1.operatorid = ".$jakuser->getVar("id");
	}

}

// DB table to use
$table = JAKDB_PREFIX.'sessions AS t1';
$table2 = ' LEFT JOIN '.JAKDB_PREFIX.'user AS t2 ON (t1.operatorid = t2.id)';
$table3 = ' LEFT JOIN '.JAKDB_PREFIX.'departments AS t3 ON (t1.department = t3.id)';

// Table's primary key
$primaryKey = 't1.id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 't1.id', 'dbjoin' => 'id', 'dt' => 0 ),
	array( 'db' => 't1.id', 'dbjoin' => 'id', 'dt' => 1, 'formatter' => function( $d, $row ) {
			return '<input type="checkbox" name="jak_delete_leads[]" class="highlight" value="'.$d.'">';
		} ),
	array( 'db' => 't1.name', 'dbjoin' => 'name', 'dt' => 2, 'formatter' => function( $d, $row ) {
			return '<a data-toggle="modal" href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('leads', 'readleads', $row['id'], 1)).'" data-target="#jakModal" class="btn btn-success btn-sm"><i class="fa fa-share-square"></i></a> <a href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('live', $row['id'])).'">'.$d.'</a>';
		} ),
	array( 'db' => 't1.email', 'dbjoin' => 'email', 'dt' => 3, 'formatter' => function( $d, $row ) {
			return (filter_var($d, FILTER_VALIDATE_EMAIL) ? '<a data-toggle="modal" href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('leads', 'clientcontact', $row['id'], 1)).'" data-target="#jakModal">'.$d.'</a>' : '-');
		} ),
	array( 'db' => 't2.username', 'dbjoin' => 'username', 'dt' => 4 ),
	array( 'db' => 't3.title', 'dbjoin' => 'title', 'dt' => 5 ),
	array( 'db' => 't1.notes', 'dbjoin' => 'notes', 'dt' => 6, 'formatter' => function( $d, $row ) {
			return '<a class="btn'.(!empty($d) ? ' btn-success' : ' btn-default').' btn-sm" data-toggle="modal" href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('notes', $row['id'])).'" data-target="#jakModal"><i class="fa fa-sticky-note"></i></a>';
		} ),
	array( 'db' => 't1.deniedoid', 'dbjoin' => 'deniedoid', 'dt' => 7, 'formatter' => function( $d, $row ) {
			return ($d ? '<span class="badge badge-danger"><i class="fa fa-file-alt"></i></span>' : '<span class="badge badge-info"><i class="fa fa-comments"></i></span>');
		} ),
	array( 'db' => 't1.initiated', 'dbjoin' => 'initiated', 'dt' => 8, 'formatter' => function( $d, $row ) {
			return JAK_base::jakTimesince($d, JAK_DATEFORMAT, JAK_TIMEFORMAT);
		} )
);

die(json_encode(SSP::join( $_GET, $table, $table2, $table3, $primaryKey, $columns, $where, $where )));
?>