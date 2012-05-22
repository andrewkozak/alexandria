<?php
/**
 *  Gallery View
 */



// Inclusions
require( 'config.php' );
require( 'functions.php' );

// Open MySQL
$cnxn = openMySQL();

// Run a Query and format into image-path (id) and extension (type)
$q = "SELECT * FROM items";
$r = mysql_query( $q , $cnxn );
while( $s = mysql_fetch_assoc( $r ) )
{
  $t = "SELECT t.name
        FROM items_to_tags AS i2t
          LEFT JOIN tags AS t
            ON i2t.tag_id = t.id
        WHERE i2t.item_id = '{$s['id']}'";
  $u = mysql_query( $t , $cnxn );
  $tags = array();
  while( $v = mysql_fetch_assoc( $u ) )
  {
    $tags[] = $v['name'];
  }

  $images[] = array(
    'id'=>$s['id'] ,
    'path'=>FS_ROOT . "stacks/" . implode( '/' , createPathArray( $s['id'] ) ) . "." . $s['type'] ,
    'tags'=>trim( implode( ',' , $tags ) , ',' )
  );
}

// Close MySQL
closeMySQL( $cnxn );



?>
<html>
<head>

<title>Gallery</title>

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
      url: "actions/update_tags.php" ,
      type: 'POST' ,
      data: { 'id': id , 'tags': tags }
    })
    .done( function( response )
    {
      clearChangedTags( id )
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

<style>

ul,li,a,img
{
  padding: 0;
  margin: 0;
  text-decoration: none;
}

</style>
 
</head>
<body>

<!--
<h1>Gallery</h1>
-->

<ul>
<?php

$image_size = '200';

foreach( $images as $i )
{
 
?>
  <li style="float:left;">
<!--
    <a class="fancybox-buttons" href="<?php print $i; ?>" rel="gallery" title="alpha,bravo,charlie">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i; ?>&h=<?php print $image_size; ?>&w=<?php print $image_size; ?>&zc=1&q=100" />
-->
    <a class="fancybox-buttons" href="<?php print $i['path']; ?>" rel="gallery" title="<?php print $i['tags']; ?>">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i['path']; ?>&h=<?php print $image_size; ?>&w=<?php print $image_size; ?>&zc=1&q=100" class="alx_thumbs" style="height:<?php print $image_size; ?>px;width:<?php print $image_size; ?>px;" />
    </a>
    <div style="display:none !important;" class="tags" id="tags_<?php print $i['id']; ?>"><?php print $i['tags']; ?></div>
  </li>
<?php

}

?>
</ul>

</body>
</html>
