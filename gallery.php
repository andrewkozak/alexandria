<?php

require_once( 'config.php' );
require_once( 'functions.php' );

include( 'header.php' );

?>
<body>
<?php include( 'navbar.php' ); ?>

<ul>
<?php

foreach( $images as $i )
{
 
?>
  <li style="float:left;">
    <a class="fancybox-buttons" href="<?php print $i['path']; ?>" rel="alexandria" title="<?php print $i['tags']; ?>">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i['path']; ?>&h=<?php print IMAGE_SIZE; ?>&w=<?php print IMAGE_SIZE; ?>&zc=1&q=100" class="alx_thumbs" style="height:<?php print IMAGE_SIZE; ?>px;width:<?php print IMAGE_SIZE; ?>px;" />
    </a>
    <div style="display:none;" class="tags" id="tags_<?php print $i['id']; ?>"><?php print $i['tags']; ?></div>
  </li>
<?php

}

?>
</ul>

</body>
</html>
