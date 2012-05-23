<?php

if(  isset($_GET['t'])  &&  strlen($_GET['t']) > 0  )
{
  $get_tags = explode( ',' , $_GET['t'] );
  foreach( $get_tags as $gt )
  {
    $taggers[] = new AlexandriaTag( $gt );  
  }  

  $request_tags_raw = explode( ',' , $_GET['t'] );
  $request_tags = array();
  $request_tags_plus = array();
  $request_tags_minus = array();
  foreach( $request_tags_raw as $raw )
  {
    if( substr( $raw , 0 , 1 ) == "-" )
    {
      if( is_numeric( $tag_id = substr( trim($raw) , 1 ) ) )
      {
        $request_tags_minus[] = $tag_id;
        $request_tags[] = array( 'include'=>false , 'id'=>$tag_id );
      }
    }
    else if( is_numeric( $tag_id = trim($raw) ) )
    {
      $request_tags_plus[] = $tag_id;
      $request_tags[] = array( 'include'=>true , 'id'=>$tag_id );
    }
  }

  if( count($request_tags_plus) > 0 )
  {
    $tag_names = tagIdToName( $request_tags_plus , true );
  }
  else
  {
    $tag_names = array();
  }

  foreach( $request_tags_minus as $rtm )
  {
    $tag_names[] = '-' . tagIdToName( $rtm );
  }

  $files_array = fileTagToFile( $request_tags );
}
else
{
  $cnxn = openMySQL();
  // Run a Query and format into image-path (id) and extension (type)
  $q = "SELECT `id` , `type` FROM items";
  $r = mysql_query( $q , $cnxn );
  while( $s = mysql_fetch_assoc( $r ) )
  {
    $files_array[] = $s;
  }
  closeMySQL( $cnxn );
}

$images = array();
$items = array();
foreach( $files_array as $file )
{
  $items[] = new AlexandriaItem( $file['id'] , $file['type'] );

  $images[] = array(
    'id'=>$file['id'] ,
    'path'=>FS_ROOT . "stacks/" . implode( '/' , createPathArray($file['id']) ) . "." . $file['type'] ,
    'tags'=>trim( implode( ',' , fileGetTagNames($file['id']) ) , ',' )
  );
}

?>
<html>
<head>

<title><?php 
  print strlen($page_title) > 0 ? $page_title . ' | ' : ""; 
?>Alexandria</title>

<!-- Alexandria -->
<link rel="stylesheet" type="text/css" href="css/reset.css" />

<!-- jQuery -->
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>

<!-- fancyBox -->
<link rel="stylesheet" type="text/css" href="third-party/fancybox/source/jquery.fancybox.css" />
<script type="text/javascript" src="third-party/fancybox/source/jquery.fancybox.js"></script>

<!-- fancyBox helpers - button, thumbnail and/or media -->
<!--
<link rel="stylesheet" href="third-party/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" type="text/css" media="screen" />
<script type="text/javascript" src="third-party/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
<script type="text/javascript" src="third-party/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
<link rel="stylesheet" href="third-party/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.6" type="text/css" media="screen" />
<script type="text/javascript" src="third-party/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.6"></script>
-->

<!-- Alexandria -->
<script type='text/javascript'>

fs_root = '<?php print FS_ROOT; ?>';
changed_tags = new Array;
thumb_size = '200';



function storeChangedTags( id )
{
  var curr = $('input#input_'+id).val().replace(/,[\s]*$/,'');
  while( curr.match(/, /) ){ curr = curr.replace( /, / , ',' ); }
  $('div#tags_'+id).html( curr );
  
  if( $.inArray( id , changed_tags ) == -1 )
  {
    changed_tags[ changed_tags.length ] = id;
  }
}



function clearChangedTags( id )
{
  changed_tags = $.grep( changed_tags , function(target) 
  {
    return target != id;
  });
}



function resetThumbSize()
{
  var width = $(window).width();

  var down_size = thumb_size;
  var down_row = Math.floor( width / down_size );
  while( width - ( down_row * down_size ) != 0 )
  {
    down_row--;
    down_size = Math.floor( width / down_row );
  }
  
  var up_size = thumb_size;
  var up_row = Math.floor( width / up_size );
  while( width - ( up_row * up_size ) != 0 )
  {
    up_row++;
    up_size = Math.floor( width / up_row );
  }
  
  if( Math.abs( thumb_size - down_size ) < Math.abs( thumb_size - up_size ) )
  {
    $('img.alx_thumbs').width( down_size );
    $('img.alx_thumbs').height( down_size );
  }
  else
  {
    $('img.alx_thumbs').width( up_size );
    $('img.alx_thumbs').height( up_size );
  }
}

function submitTags()
{
  for( var i = 0 ; i < changed_tags.length ; i++ )
  {
    var id = changed_tags[i];
    var tags = $('div.tags#tags_' + id ).html().split(',');
    var jqxhr = $.ajax(
    {
      url: "actions/tags/update.php" ,
      type: 'POST' ,
      data: { 'id': id , 'tags': tags }
    })
    .done( function( response )
    {
      clearChangedTags( id );
    })
    .fail( function() 
    {
      console.log( "AJAX error" ); 
    })
    .always( function() 
    { 
      //console.log( "AJAX complete" ); 
    });
  }
}

</script>
<script type='text/javascript' src='js/alx_gallery.js'></script>

<!-- Alexandria -->
<link rel="stylesheet" type="text/css" href="css/main.css" />

</head>
