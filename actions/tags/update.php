<?php

// Inclusions
require_once( '../config.php' );
require_once( '../functions.php' );

// Apply tags as sent
applyTags( $_POST['id'] , $_POST['tags'] );

//print_r( $_POST );

?>
