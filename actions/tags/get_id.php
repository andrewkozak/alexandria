<?php

// Inclusions
require_once( '../../config.php' );
require_once( '../../functions.php' );

//print_r( $_POST );

if( strlen( $_POST['name'] ) > 0 )
{
  $cnxn = openMySQL();

  $q = "SELECT `id`
        FROM tags
        WHERE `name`='" . mysql_real_escape_string( $_POST['name'] ) . "'";
  $r = mysql_query( $q , $cnxn );
  $s = mysql_fetch_assoc( $r );
  
  closeMySQL( $cnxn );

  print $s['id'];
}

?>
