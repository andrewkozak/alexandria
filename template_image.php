<?php

require_once( 'config.php' );
require_once( 'functions.php' );
require_once( 'classes/include.php' );

$i = new AlexandriaItem( $_POST['item_id'] );

?>
<div class="div_tags_outer" id="div_tags_outer_<?php print $i->id; ?>">
  <script type="text/javascript">

  var bksp_<?php print $i->id; ?> = new Object();
  bksp_<?php print $i->id; ?>.count = 0;

  $('input#alx_input_tmp_<?php print $i->id; ?>').keyup( function(e)
  { 
    k = ( e.keyCode ? e.keyCode : e.which ); 
    
    //   8 Backspace
    //   9 Tab
    //  13 Enter
    //  27 Esc
    // 188 , [Comma]
    
    // Check for Backspace
    if( k == 8 )
    {
      if( $(this).val().length != 0 )
      {
        bksp_<?php print $i->id; ?>.count = 0;
      }
      else
      {
        if( bksp_<?php print $i->id; ?>.count++ > 1 )
        {
          var extant = $('div#alx_div_tag_names_<?php print $i->id; ?>').html().trim();
          while( extant.match( /(\s,|,\s)/ ) )
          {
            extant = extant.replace( /(\s,|,\s)/ , ',' );
          }
          extant = extant.split(',');       

          var last = extant.pop();
   
          $('div#alx_div_tag_names_<?php print $i->id; ?>').html( extant.join(',') );
          $(this).val( last );

          alxSubmitTags( '<?php print $i->id; ?>' );
        }
      }
    }
    else if(  k == 9  ||  k == 13  ||  k == 27  ||  k == 188  )
    {
      var value = $(this).val().trim().replace( /^\s*/ , '' ).replace( /\s*,$/ , '' ); 
      while( value.match(/\s\s+/) ){ value = value.replace( /\s\s/ , ' ' ); } 

      if( value.length > 0 )
      {
        var extant = $('div#alx_div_tag_names_<?php print $i->id; ?>').html().trim().replace( /,$/ , '' ); 
        if( extant.length > 0 ){ value = ',' + value; } 

        $('div#alx_div_tag_names_<?php print $i->id; ?>').html( extant + value ); 
      }

      $(this).val( '' ); 

      alxSubmitTags( '<?php print $i->id; ?>' );

      // On <Tab> or <Enter>
      if(  k == 9  ||  k == 13  )
      { 
        // If <Shift> is down, move back one item
        if( e.shiftKey ){ $.fancybox.prev(); }
        // Otherwise, move forward one item 
        else{ $.fancybox.next(); } 
      }
      // On <Esc> close 
      else if( k == 27 ){ $.fancybox.close(); } 
    } 
    else if( $(this).val().length > 0 )
    {
      bksp_<?php print $i->id; ?>.count = 0;            
    }
 
    // Stop the key event from propagating
    e.stopImmediatePropagation(); 
  });
  </script>
  <div id="alx_div_false_input_<?php print $i->id; ?>" class="alx_div_false_input" style="">
    <div id="alx_div_tag_graphics_<?php print $i->id; ?>" class="alx_div_tag_graphics">
<?php

foreach( $i->getItemTags() as $t )
{

?>

      <span class="alx_span_tag">
        <span class="alx_span_tag_name"><?php print $t['name']; ?></span>
      </span>

<?php

}

?>
      <div style="clear:both;"></div>
    </div>
    <span id="alx_span_buffer_<?php print $i->id; ?>"></span>
    <input id="alx_input_tmp_<?php print $i->id; ?>" class="alx_input_tmp" type="text" value="" tabindex="-1" />
  </div>
</div><!-- .tag_input_outer -->
