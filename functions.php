<?php
/**
 *  Functions Warehouse (for now)
 */



/**
 *  MySQL
 */
function openMySQL()
{
  $cnxn = mysql_connect( MYSQL_LOCATION , MYSQL_USERNAME , MYSQL_PASSWORD );
  mysql_select_db( MYSQL_DATABASE , $cnxn );

  return $cnxn;
}



function closeMySQL( &$cnxn )
{
  mysql_close( $cnxn );

  return;
}



/**
 *  Examine the proposed stack path and create directories if needed
 *
 *  @param $stack Array describing the stack path
 *  @return $path Last stack path checked
 */
function buildStacks( $stacks )
{
  for( $i=1 ; $i < ( count($stacks) ) ; $i++ )
  {
    $path = "stacks";

    for( $j=0 ; $j < $i ; $j++ )
    {
      $path .= "/" . $stacks[$j];  
    }

    if( !is_dir( $path ) )
    {
      mkdir( $path );
    }
  }

  return $path;
}



function createPathArray( $filehash )
{
  $array = str_split( $filehash );

  $path = '';
  for( $i=0 ; $i < count( $array ) ; $i++ )
  {
    $path .= $array[$i];

    if( ($i+1) % 4 == 0 )
    {
      $path_array[] = $path;
      $path = '';
    }
  }

  return $path_array;
}



/**
 *  Generate a composite hash from file data
 */
function generateHash( $filepath )
{
  // Open the file for reading
  $handle = fopen( $filepath , 'r' );
  
  // Initialize MD5 hash
  $md5 = hash_init( 'md5' );
  
  // Initiliaze SHA1 hash
  $sha = hash_init( 'sha1' );
  
  // Loop over the file in 128-byte chunks
  while( $chunk = fread( $handle , 128 ) )
  {
    // Update MD5 hash 
    hash_update( $md5 , $chunk );
    // Update SHA1 hash
    hash_update( $sha , $chunk );
  }
  
  // Finalize MD5 hash
  $mhash = hash_final( $md5 );
  // Finalize SHA1 hash
  $shash = hash_final( $sha );
  
  // Close the file
  fclose( $handle );  
  
  // Return MD5 . SHA1
  return $mhash . $shash;
}



?>
