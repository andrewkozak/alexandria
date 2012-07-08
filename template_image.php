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

  $('div.alx_div_tag').hover( 
    function(){ $(this).children('a').css( 'background-color' , 'rgb(120, 120, 112)' ); } ,
    function(){ $(this).children('a').css( 'background-color' , 'rgb(160, 160, 150)' ); }
  );

  $('span.alx_span_tag_remove').live( 'click' , function(e)
  {
    var item_id = $(this).attr('id').split('__')[0].split('_')[1];
    var tag_name = $(this).siblings('a.alx_a_tag_link').html().trim();
    var curr_tag_names = $('div#alx_div_tag_names_'+item_id).html().split(',');
    var tag_name_index = $.inArray( tag_name , curr_tag_names );
    
    if( tag_name_index != -1 )
    {
      curr_tag_names.splice( tag_name_index , 1 );

      new_tag_names = curr_tag_names.join(',');

      $('div#alx_div_tag_names_'+item_id).html( new_tag_names );

      alxSubmitTags( item_id );
    }

    e.stopImmediatePropagation(); 
  });
  
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
      if( $(this).val().trim().length != 0 )
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
      // Trim whitespace, force lowercase, and escape single quotes
      var value = $(this).val().trim().replace( /^\s*/ , '' ).replace( /\s*,$/ , '' );
          value = value.toLowerCase(); 
          value = value.replace( /'/ , '\'' );
      while( value.match(/\s\s+/) ){ value = value.replace( /\s\s/ , ' ' ); } 

      if( value.length > 0 )
      {
        var extant = $('div#alx_div_tag_names_<?php print $i->id; ?>').html().trim().replace(/,$/,''); 
       
        var extant_tags = new Array();
 
        if( extant.length > 0 ){ extant_tags = extant.split(','); }

        extant_tags[ extant_tags.length ] = value;

        extant_tags.sort();        

        $('div#alx_div_tag_names_<?php print $i->id; ?>').html( extant_tags.join(',') ); 
      }

      $(this).val( '' ); 

      if( k != 27 )
      {
        alxSubmitTags( '<?php print $i->id; ?>' );
      }

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
    <div id="alx_div_tag_graphics_<?php print $i->id; ?>" class="alx_div_tag_graphics"
         style="margin-bottom:10px;" >

<?php

foreach( $i->getItemTags() as $t )
{

?>

      <div class="alx_div_tag">
        <a class="alx_a_tag_link" href="gallery.php?t=<?php print $t['id']; ?>">
          <?php print $t['name']; ?>
        </a>
        <span class="alx_span_tag_remove" id="i_<?php print $i->id; ?>__t_<?php print $t['id']; ?>">&nbsp;</span>
      </div>

<?php

}

?>

      <div style="clear:both;"></div>
    </div>
    <input id="alx_input_tmp_<?php print $i->id; ?>" class="alx_input_tmp" type="text" value="" tabindex="-1" />
  </div>
</div><!-- .tag_input_outer -->
