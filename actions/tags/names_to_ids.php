<?php

// Inclusions
require_once( '../../config.php' );
require_once( '../../functions.php' );

//print_r( $_POST );

$cnxn = openMySQL();

$query_names_plus = array();
$query_names_minus = array();
foreach( $_POST['names'] as $name )
{
  if( substr( $name , 0 , 1 ) == '-' )
  {
    $query_names_minus[] = mysql_real_escape_string( substr($name,1) );
  }
  else
  {
    $query_names_plus[] = mysql_real_escape_string( $name );
  }
}

$q = "SELECT `id`
      FROM tags
      WHERE `name` IN ('" . implode("','",$query_names_plus) . "')
      GROUP BY `id`";
$r = mysql_query( $q , $cnxn );

$tag_nums = '';
while( $s = mysql_fetch_assoc( $r ) )
{
  $tag_nums .= $s['id'] . ',';
}

$q = "SELECT `id`
      FROM tags
      WHERE `name` IN ('" . implode("','",$query_names_minus) . "')
      GROUP BY `id`";
$r = mysql_query( $q , $cnxn );

while( $s = mysql_fetch_assoc( $r ) )
{
  $tag_nums .= '-' . $s['id'] . ',';
}

print trim( $tag_nums , ',' );

closeMySQL( $cnxn );

?>
