<?php

// Inclusions
require_once( '../../config.php' );
require_once( '../../functions.php' );

//print_r( $_POST );

$cnxn = openMySQL();

$query_names = array();
foreach( $_POST['names'] as $name )
{
  $query_names[] = mysql_real_escape_string( $name );
}

$q = "SELECT `id` 
      FROM tags
      WHERE `name` IN ('" . implode( "','" , $query_names ) . "')
      GROUP BY `id`";
$r = mysql_query( $q , $cnxn );

$tag_nums = '';
while( $s = mysql_fetch_assoc( $r ) )
{
  $tag_nums .= $s['id'] . ',';
}

print trim( $tag_nums , ',' );

closeMySQL( $cnxn );

?>
