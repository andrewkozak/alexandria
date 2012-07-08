<?php

$files_array = array();

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

www_root = '<?php print WWW_ROOT; ?>';
thumb_size = '<?php print IMAGE_SIZE; ?>';



function alxSubmitTags( id )
{
  var names = $('div#alx_div_tag_names_'+id).html();

  /* @todo Is this being used? */
  //var graphics = $('div#alx_div_tag_graphics_'+id).html();

  var jqxhr = $.ajax(
  {
    url: "actions/item/update_tags.php" ,
    async: false ,
    type: 'POST' ,
    data: { 'item_id': id , 'tag_names': names.toLowerCase() } ,
    dataType: 'json' 
  })
  .done( function( response )
  {
    alxShowTags( id , response );
  });

  return;
}



function alxShowTags( id , tags )
{
  var new_graphics = '';

  for( t in tags )
  {
    new_graphics += '<div class="alx_div_tag"><a class="alx_a_tag_link" href="gallery.php?t=' + tags[t].id + '">' + tags[t].name + '</a><span class="alx_span_tag_remove" id="i_' + id + '__t_' + tags[t].id + '">X</span></div>';
  }
  
  $('div#alx_div_tag_graphics_'+id).html( new_graphics + '<div style="clear:both;"></div>' );

  return;
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
