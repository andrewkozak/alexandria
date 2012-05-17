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
/*
  print "<pre>";
  print $s['id'];
  print "";
  print_r( $split = createPathArray( $s['id'] ) );
  print "";
  print ( $path = "stacks/" . implode( '/' , $split ) . "." . $s['type'] );
  print "</pre>";
  $images[] = $path;
*/
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

</head>
<body>

<h1>Gallery</h1>

<?php

foreach( $images as $i )
{

?>
  <img src="<?php print $i; ?>" style="width:200px;" ><br />
<?php

}

?>

</body>
</html>
