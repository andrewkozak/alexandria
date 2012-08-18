<?php

$page_title = "Tags";

require_once( 'config.php' );
require_once( FS_ROOT . 'functions.php' );
require_once( FS_ROOT . 'classes/include.php' );

$cnxn = openMySQL();

$q = "SELECT t.id , t.name
      FROM tags AS t
      ORDER BY t.name";
$r = mysql_query( $q , $cnxn );

$tags_array = array();
while( $s = mysql_fetch_assoc( $r ) )
{ 
  $tags_array[] = $s; 
}

closeMySQL( $cnxn );



foreach( $tags_array as $t )
{

?>

<div><a href="gallery.php?t=<?php print $t['id']; ?>"><?php print $t['name']; ?></a></div>

<?php

}

?>



<?php

$tags_array = array();

?>
