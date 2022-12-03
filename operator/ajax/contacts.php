<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2020 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[config.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!file_exists('../../class/ssp.class.php')) die('ajax/[ssp.class.php] config.php not exist');
require_once '../../class/ssp.class.php';

// $CONTACTS_ALL = $jakdb->select($jaktable, ["id", "depid", "name", "email", "ip", "countrycode", "country", "city", "answered", "sent"], ["ORDER" => ["sent" => "DESC"], "LIMIT" => [$contacts->limit]]);

// DB table to use
$table = JAKDB_PREFIX.'contacts';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'id', 'dt' => 0 ),
	array( 'db' => 'id', 'dt' => 1, 'formatter' => function( $d, $row ) {
			return '<input type="checkbox" name="jak_delete_contacts[]" class="highlight" value="'.$d.'">';
		} ),
	array( 'db' => 'name', 'dt' => 2, 'formatter' => function( $d, $row ) {
			return '<a data-toggle="modal" href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('contacts', 'readmsg', $row['id'], 1)).'" data-target="#jakModal">'.$d.'</a>';
		} ),
	array( 'db' => 'email', 'dt' => 3, 'formatter' => function( $d, $row ) {
			return (filter_var($d, FILTER_VALIDATE_EMAIL) ? '<a data-toggle="modal" href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('contacts', 'readmsg', $row['id'], 1)).'" data-target="#jakModal">'.$d.'</a>' : '-');
		} ),
	array( 'db' => 'answered', 'dt' => 4, 'formatter' => function( $d, $row ) {
			return ($d != "1980-05-06 00:00:00" ? JAK_base::jakTimesince($d, JAK_DATEFORMAT, JAK_TIMEFORMAT) : '-');
		} ),
	array( 'db' => 'sent', 'dt' => 5, 'formatter' => function( $d, $row ) {
			return JAK_base::jakTimesince($d, JAK_DATEFORMAT, JAK_TIMEFORMAT);
		} )
);

die(json_encode(SSP::simple( $_GET, $table, $primaryKey, $columns )));
?>