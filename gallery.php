<?php

$page_title = "Gallery";

require_once( 'config.php' );
require_once( 'functions.php' );
require_once( 'classes/include.php' );

include( 'header.php' );

?>
<body>
<?php include( 'navbar.php' ); ?>

<ul>
<?php

foreach( $items as $i )
{
debug( $i );

?>
  <li style="float:left;">
    <a class="fancybox-buttons" href="<?php print $i->src ?>" rel="alexandria" title="<?php print $i->tag_names; ?>">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i->src; ?>&h=<?php print IMAGE_SIZE; ?>&w=<?php print IMAGE_SIZE; ?>&zc=1&q=100" class="alx_thumbs" style="height:<?php print IMAGE_SIZE; ?>px;width:<?php print IMAGE_SIZE; ?>px;" />
    </a>
    <div style="display:none;" class="tags" id="tags_<?php print $i->id; ?>"><?php print implode(', ',$i->tag_names); ?></div>
  </li>
<?php

}

?>
</ul>

</body>
</html>
