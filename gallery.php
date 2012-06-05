<?php

$page_title = "Gallery";

require_once( 'config.php' );
require_once( FS_ROOT . 'functions.php' );
require_once( FS_ROOT . 'classes/include.php' );

include( 'header.php' );

?>
<body>

<?php include( 'navbar.php' ); ?>

<ul>
<?php

foreach( $items as $i )
{

?>
  <li style="float:left;">
    <a class="fancybox-buttons" href="<?php print $i->getItemSrc(); ?>" rel="alexandria" title="">
      <img src="third-party/timthumb/timthumb.php?src=<?php print $i->getItemSrc(); ?>&h=<?php print IMAGE_SIZE; ?>&w=<?php print IMAGE_SIZE; ?>&zc=1&q=100" class="alx_img_thumbs" style="height:<?php print IMAGE_SIZE; ?>px;width:<?php print IMAGE_SIZE; ?>px;" />
    </a>
    <div style="display:none;" class="alx_div_tag_names" id="alx_div_tag_names_<?php print $i->id; ?>"><?php print count($i->getItemTagNames()) > 0 ? implode(',',$i->getItemTagNames()) : ''; ?></div>
  </li>
<?php

}

?>
</ul>

</body>
</html>
