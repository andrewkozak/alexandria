<?php
/**
 *  Functions Warehouse (for now)
 */



// Inclusions
require_once( 'config.php');



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



function applyTags( $id , $tags )
{
  if(  count($tags) > 0  &&  strlen($id) > 0  )
  {
    $cnxn = openMySQL();

    $tag_array = array();

    foreach( $_POST['tags'] as $tag )
    {
      $trimmed_tag = trim( $tag );
      
      if( strlen( $trimmed_tag ) > 0 )
      {
        $q = "SELECT `id` , `name` 
              FROM tags 
              WHERE `name`='{$trimmed_tag}'";

        if( @mysql_num_rows( $r = mysql_query( $q ) ) !=1 )
        {
          $q = "INSERT INTO tags ( `name` )
                VALUES ( '" . mysql_real_escape_string( $trimmed_tag ) . "' )";
          mysql_query( $q , $cnxn );

          $q = "SELECT `id` , `name` 
                FROM tags 
                WHERE `name`='{$trimmed_tag}'";

          if( @mysql_num_rows( $r = mysql_query( $q ) ) !=1 ){ return false; }
        }

        $s = mysql_fetch_assoc( $r );

        $tag_array[] = array( 'id'=>$s['id'] , 'name'=>$s['name'] );
        $new_tags[] = $s['id'];
      }
    }

    foreach( $tag_array as $t )
    {
      $q = "INSERT INTO items_to_tags ( `item_id` , `tag_id` ) 
            VALUES ( '{$id}' , '{$t['id']}' )";
      mysql_query( $q , $cnxn );
    }
    
    $q = "SELECT t.id
          FROM tags AS t
            JOIN items_to_tags AS i2t
              ON t.id = i2t.tag_id
          WHERE i2t.item_id='{$id}'";
    $r = mysql_query( $q , $cnxn );
    while( $s = mysql_fetch_assoc( $r ) )
    {
      $old_tags[] = $s['id'];
    }

    $q = "DELETE FROM items_to_tags 
          WHERE `tag_id` IN ('" . 
            implode("','",array_diff($old_tags,$new_tags) )
          . "')";

    mysql_query( $q , $cnxn );

    closeMySQL( $cnxn );

    return true;
  }
  else
  {
    return false;
  }
}



/**
 *  Tags :: ID to Name
 *
 *  Accepts an ID or an array of ID's and returns
 *  a Name or an array of Names.
 */
function tagIdToName( $id , $array=false )
{
  if( is_array( $id ) )
  {
    foreach( $id as $i )
    { 
      if( is_numeric( trim($i) ) )
      {
        $query_array[] = trim($i);
      }
    }
  }
  else
  {
    if( is_numeric( trim($id) ) )
    {
      $query_array[] = trim($id);
    }
  }

  $cnxn = openMySQL();

  $q = "SELECT tags.name
        FROM tags
        WHERE tags.id IN ('" . implode( "','" , $query_array ) . "')
        ORDER BY tags.name";
  $r = mysql_query( $q , $cnxn );
  $return_array = array();
  while( $s = mysql_fetch_assoc($r) )
  {
    $return_array[] = $s['name'];
  }

  closeMySQL( $cnxn );

  return (  count($return_array) > 1  ||  $array == true  )
    ? $return_array : $return_array[0];
}





?>
