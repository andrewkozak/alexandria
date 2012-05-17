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
  $images[] = "stacks/" . implode( '/' , createPathArray( $s['id'] ) ) . "." . $s['type'];
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

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
<script type='text/javascript' src='js/alx_gallery.js'></script>

</head>
<body>

<h1>Gallery</h1>

<?php

foreach( $images as $i )
{

?>
  <span>
    <img class="alx_thumbnail" src="<?php print $i; ?>" style="width:200px;" />
  </span>
<?php

}

?>

</body>
</html>
