$(document).ready( function()
{
  if( $('input#tag_search').val().length > 0 )
  {
    $('input#tag_search').focus();
    $('input#tag_search').val( $('input#tag_search').val() );
  }
  //TODO Try to smooth resizing by only triggering on even window widths
  //if( $(window).width % 2 == 0 )
  //{
    resetThumbSize();
  //}

  $(window).resize( function()
  {
    //TODO Try to smooth resizing by only triggering on even window widths
    //if( $(window).width % 2 == 0 )
    //{ 
      resetThumbSize();
    //}
  });

  $("a[rel=gallery]").fancybox(
  {
    'transitionIn' : 'elastic',
    'transitionOut' : 'elastic'		
  });

  $('.fancybox-buttons').fancybox(
  {
    openEffect  : 'none' ,
    closeEffect : 'none' ,
    
    prevEffect : 'none' ,
    nextEffect : 'none' ,

    closeBtn  : true ,

    helpers : 
    {
      title : 
      {
	type : 'inside'
      } ,
      buttons : {}
    } ,
   
    onUpdate : function()
    {
      // Get the hash
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }
      
      // Fill up the overlay
/*TODO Work on overlays later
      var tags = $('div#tags_'+href).html().split(',');
      var span_inner = '';
for( var j = 0; j < tags.length ; j++ )
{
  span_inner += '<span class="overlaid_tag" >' + tags[j] + '</span>';
}
      $('span#tags_overlay_'+href).html( span_inner );
*/
      // Bring focus() to the input 
      $('input#input_'+href).focus();
      $('input#input_'+href).val( $('input#input_'+href).val() );
      // Clear the tagging queue
      submitTags();
    } ,
 
    beforeClose : function()
    {
      submitTags();
    } ,
    
    beforeLoad : function() 
    {
      // Extract the ID from the href attribute
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }

      // Extract the tags from the title attribute
      var tags = $('div.tags#tags_' + href ).html().split(',');
      
      // Begin markup for input box
      var box = '<script type="text/javascript">$(\'input#input_' + href + '\').keyup( function(e){ k = ( e.keyCode ? e.keyCode : e.which ); storeChangedTags(\'' + href + '\'); e.stopImmediatePropagation(); if( k == 13 ){ if( e.shiftKey ){ $.fancybox.prev(); } else{ $.fancybox.next(); } } else if( k == 27 ){ $.fancybox.close(); } });</script><div class="tag_input_outer" id="tag_input_outer_' + href + '"><span class="tags_overlay" id="tags_overlay_' + href + '"></span> <input id="input_' + href + '" type="text" class="alx_tags" value="';
      
      // Put each tag in input box
      if( tags.length > 0 )
      {
        for( var i = 0 ; i < tags.length ; i++ )
        {
          if( tags[i].length > 0 )
          {
            box += tags[i].trim() + ', ';
          }
        }
      }

      // Close markup for input box
      box += '" /></div><!-- .tag_input_outer -->';

      // Set input box as fancybox title
      this.title = box;
    }
  });
});
