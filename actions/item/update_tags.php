<?php

require_once( '../../config.php' );
require_once( FS_ROOT . 'functions.php' );
require_once( FS_ROOT . 'classes/include.php' );

$i = new AlexandriaItem( $_POST['item_id'] );
$i->setItemTags( $_POST['tag_names'] );

print json_encode($i->getItemTags());

?>
