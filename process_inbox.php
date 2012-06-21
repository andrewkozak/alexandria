<?php
/**
 *  Process All New Files in /inbox
 */



// Include functions
$page_title = "Process Inbox";

require_once( 'config.php' );
require_once( FS_ROOT . 'functions.php' );
require_once( FS_ROOT . 'classes/include.php' );

// Globals
$input_path = "inbox";

$inbox = opendir( "inbox" );

while( ($entry = readdir($inbox)) !== false )
{
  if( substr( $entry , 0 , 1 ) != "." )
  {
    $input_file = $entry; 
    $input_type = substr( $input_file , strrpos($input_file,'.')+1 );
    $output_type = strtolower( substr( $input_file , strrpos($input_file,'.')+1 ) );
      if( $output_type == 'jpeg' ){ $output_type = 'jpg'; }
    $input_name = substr( $input_file , 0 , strlen($input_file)-strlen($input_type)-1 );
    
    $hash = generateHash( $input_path . "/" . $input_file );

    $path_array = createPathArray( $hash );

    $stack_path = buildStacks( $path_array );

    $input_full_path = FS_ROOT . $input_path . "/" . $input_name . "." . $input_type;
    $output_full_path = FS_ROOT . $stack_path . "/" . $path_array[ count($path_array)-1 ] . "." . $output_type;
    $thumb_full_path = FS_ROOT . $stack_path . "/t/t_" . $path_array[ count($path_array)-1 ] . "." . $output_type;
    
    $cnxn = openMySQL();
    
    $q = "INSERT INTO items ( id , name , type ) VALUES ('{$hash}','{$input_name}','{$output_type}')";
    
    mysql_query( $q , $cnxn );

    closeMySQL( $cnxn );
 
    // Check for the thumbnail
    if( !file_exists( $thumb_full_path ) )
    {
      $thumb_dir = substr( $thumb_full_path , 0 , strrpos( $thumb_full_path , '/t_' ) );
      if( !file_exists( $thumb_dir ) )
      {
        print( $thumb_dir );
        mkdir( $thumb_dir );
        $old_mask = umask(0);
        chmod( $thumb_dir , 0777 );
        umask( $old_mask );
      }
     
      if( image_resize( $input_full_path , $thumb_full_path , ( 2 * ( (int) IMAGE_SIZE ) ) , ( 2 * ( (int) IMAGE_SIZE ) ) , 1 ) )
      {
        print "Success! Create new thumbnail at {$thumb_full_path}<br />";
      }
    }
    else
    {
      print "Failure to create thumbnail for {$input_full_path}; already exists<br />";
    }



    if( !file_exists( $output_full_path ) )
    {
      if( rename( $input_full_path , $output_full_path ) )
      {
        print "Success! Stored new image at {$output_full_path}<br />";
      }
    }
    else
    {
      print 'File: ' . $input_full_path . ' already exists at: ' . $output_full_path;
      print '<br />';
      print '<img src="third-party/timthumb/timthumb.php?src=' . $input_full_path . '&h=' . IMAGE_SIZE . '&w=' . IMAGE_SIZE . '&zc=1&q=100" style="height:' . IMAGE_SIZE . 'px;width:' . IMAGE_SIZE . 'px;margin-right: 20px;" />';
      print '<img src="third-party/timthumb/timthumb.php?src=' . $output_full_path . '&h=' . IMAGE_SIZE . '&w=' . IMAGE_SIZE . '&zc=1&q=100" style="height:' . IMAGE_SIZE . 'px;width:' . IMAGE_SIZE . 'px;" />';
      print '<br />';
      print '<br />';
    }
  }
}



?>
