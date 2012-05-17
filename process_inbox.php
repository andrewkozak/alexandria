<?php
/**
 *  Process All New Files in /inbox
 */



// Include functions
require_once( 'functions.php' );

// Globals
$input_path = "inbox";

$inbox = opendir( "inbox" );

while( ($entry = readdir($inbox)) !== false )
{
  if( $entry != "."  &&  $entry != ".."  ) 
  {
    $input_file = $entry; 
    $input_type = substr( $input_file , strrpos($input_file,'.')+1 );
    $output_type = strtolower( substr( $input_file , strrpos($input_file,'.')+1 ) );
      if( $output_type == 'jpeg' ){ $output_type = 'jpg'; }
    $input_name = substr( $input_file , 0 , strlen($input_file)-strlen($input_type)-1 );
print "FILE: " . $input_file . " :";
    $hash = generateHash( $input_path . "/" . $input_file );

    $path_array = createPathArray( $hash );

    $stack_path = buildStacks( $path_array );
    
    $cnxn = openMySQL();
    
    $q = "INSERT INTO items ( id , name , type ) VALUES ( '{$hash}' , '{$input_name}' , '{$output_type}' )";
    
    mysql_query( $q , $cnxn );

    closeMySQL( $cnxn );

    rename( $input_path . "/" . $input_name . "." . $input_type , $stack_path . "/" . $path_array[ count($path_array)-1 ] . "." . $output_type );
  }
}

print "test";



?>
