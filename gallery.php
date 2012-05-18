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
  $images[] = array(
    'path'=>FS_ROOT . "stacks/" . implode( '/' , createPathArray( $s['id'] ) ) . "." . $s['type'] ,
    'tags'=>"alpha,bravo,charlie"
  );
  //$images[] = FS_ROOT . "stacks/" . implode( '/' , createPathArray( $s['id'] ) ) . "." . $s['type'];
  //$images[] = $i;
}
  

// Close MySQL
closeMySQL( $cnxn );

// Batch through all images
  // build path from hash/id+ext
  // Fetch image


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

foreach( $images as $i )
{
 
?>
  <li style="height:200px;width:200px;float:left;">
<!--
    <a class="fancybox-buttons" href="<?php print $i; ?>" rel="gallery" title="alpha,bravo,charlie">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i; ?>&h=200&w=200&zc=1&q=100" />
-->
    <a class="fancybox-buttons" href="<?php print $i['path']; ?>" rel="gallery" title="<?php print $i['tags']; ?>">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i['path']; ?>&h=200&w=200&zc=1&q=100" />
    </a>
  </li>
<?php

}

?>
</ul>

</body>
</html>
