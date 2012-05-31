<?php

if(  isset($_GET['t'])  &&  strlen($_GET['t']) > 0  )
{
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
  $q = "SELECT `id` , `type` FROM items";
  $r = mysql_query( $q , $cnxn );
  while( $s = mysql_fetch_assoc( $r ) ){ $files_array[] = $s; }
  closeMySQL( $cnxn );
}

$items = array();
foreach( $files_array as $f )
{
  $i = new AlexandriaItem( $f['id'] );
  $i->type = $f['type'];  
  $items[] = $i;
}

?>
<html>
<head>

<title><?php 
  print (  isset($page_title)  &&  strlen($page_title) > 0  ) 
    ? $page_title . ' | ' : ""; 
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
thumb_size = '<?php print IMAGE_SIZE; ?>';



function alxSubmitTags( id )
{
console.log( "Submitting tags for: " + id );

  var tmp = $('input#alx_input_tmp_'+id).val();

  var names = $('div#alx_div_tag_names_'+id).html();

  var graphics = $('div#alx_div_tag_graphics_'+id).html();

  var jqxhr = $.ajax(
  {
    url: "actions/item/update_tags.php" ,
    type: 'POST' ,
    async: false ,
    data: { 'id': id }
  })
  .done( function( response )
  {
console.log( "AJAX success" );
    alxShowTags( id );
  })


  return;
}



function alxShowTags( id )
{
console.log( "Showing tags for :" + id );

  var tmp = $('input#alx_input_tmp_'+id).val();
console.log( "In input#tmp:" );
console.log( tmp );
  var names = $('div#alx_div_tag_names_'+id).html();
console.log( "In div#names:" );
console.log( names );
  var graphics = $('div#alx_div_tag_graphics_'+id).html();
console.log( "In div#graphics:" );
console.log( graphics );

  return;
}


/*
function newSubmitTags( id )
{
  // Get the tags as the value of the hidden input
  var tags = $('input#input_'+id).val();
 
  // Remove any spaces around commas
  while( tags.match( /(\s,|,\s)/ ) )
  {
    tags = tags.replace( /(\s,|,\s)/ , ',' );
  }
  
  // Remove trailing comma
  tags = tags.trim().replace( /,$/ , '' );
 
  // Split the cleaned array on commas 
  var tags = tags.split(',');

  // Send AJAX request to update the tags for the item
console.log( "newSubmitTags is sending: " + tags );
  var jqxhr = $.ajax(
  {
    url: "actions/tags/update.php" ,
    type: 'POST' ,
    async: false ,
    data: { 'id': id , 'tags': tags }
  })
  .done( function( response )
  {
    //console.log( "AJAX success" );
console.log( id );
console.log( tags );
console.log('div.tags#tags_'+id);
    $('div.tags#tags_'+id).html( tags.join(',') );
console.log('div.tags#tags_'+id);
  })
  .fail( function() 
  {
    console.log( "AJAX error" ); 
  })
  .always( function() 
  { 
    //console.log( "AJAX complete" ); 
  });
  
  return;
}




function tagsToDiv( id )
{
  var tags = $('input#input_'+id).val();
  while( tags.match( /(\s,|,\s)/ ) )
  {
    tags = tags.replace( /(\s,|,\s)/ , ',' );
  }
  tags = tags.replace( /,$/ , '' );
  
  var tags = tags.split(',');

  var div_html = '';
  for( var i = 0 ; i < tags.length ; i++ )
  {
    if( tags[i].length > 0 )
    {
      var jqxhr = $.ajax(
      {
        url: "actions/tags/get_id.php" ,
        type: 'POST' ,
        async: false ,
        data: { 'name': tags[i] }
      })
      .done( function( response )
      {
        div_html += '<span id="tag_tag_' + response + '" class="tag_tag"><a href="gallery.php?t=' + response + '">' + tags[i] + '</a><span class="tag_remove" onclick="console.log( \'Removing ' + response + ' from ' + id + '\' ); removeTagFromItem( ' + response + ' , ' + id + ' );">X</span></span>';
      })
      .fail( function() 
      {
        console.log( "AJAX error" ); 
      })
    }
  }
  
  $('div#div_tags_'+id).html( div_html + '<div style="clear:both;"></div>' );

  return;
}



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

*/

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
    $('img.alx_img_thumbs').width( down_size );
    $('img.alx_img_thumbs').height( down_size );
  }
  else
  {
    $('img.alx_img_thumbs').width( up_size );
    $('img.alx_img_thumbs').height( up_size );
  }
}

</script>
<script type='text/javascript' src='js/alx_gallery.js'></script>

<!-- Alexandria -->
<link rel="stylesheet" type="text/css" href="css/main.css" />

</head>
