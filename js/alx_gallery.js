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
      // Extract the ID from the href attribute
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }
      
      // Bring focus() to the input 
      $('input#input_tmp_'+href).focus();
      $('input#input_tmp_'+href).val( $('input#input_tmp_'+href).val() );
     
      //TODO Not sure we need to do this...
      //newSubmitTags( href );
    } ,
 
    beforeClose : function()
    {
      // Extract the ID from the href attribute
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }
 
      newSubmitTags( href );
    } ,
    
    beforeLoad : function() 
    {
      // Extract the ID from the href attribute
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }

      // Extract the tags from the storage div
      var tags = $('div.tags#tags_' + href ).html().split(',');
console.log( tags );

      // Put each tag in input box
      var tags_in_box = '';
      if( tags.length > 0 )
      {
        for( var i = 0 ; i < tags.length ; i++ )
        {
          if( tags[i].length > 0 )
          {
            tags_in_box += tags[i].trim() + ', ';
          }
        }
      }
      
      var box = '';
      box += '<div class="tag_input_outer" id="tag_input_outer_' + href + '">';
      box += '  <input id="input_' + href + '" type="text" class="alx_tags" value="' + tags_in_box + '" /tabindex="-1" />';
      box += '  <script type="text/javascript">$(\'input#input_tmp_' + href + '\').keyup( function(e){ k = ( e.keyCode ? e.keyCode : e.which ); if(  k == 9  ||  k == 13  ||  k == 27  ||  k == 188  ){ var value = $(this).val(); while( value.match( /\\s\\s+/ ) ){ value = value.trim().replace( /\\s\\s/ , \' \' ); } var extant = $(\'input#input_' + href + '\').val().trim().replace( /,$/ , \'\'); if( extant.length > 0 ){ value = \', \' + value; } $(\'input#input_' + href + '\').val( extant + value ); $(this).val( \'\' ); newSubmitTags( \'' + href + '\' ); tagsToDiv( \'' + href + '\' ); if(  k == 9  ||  k == 13  ){ if( e.shiftKey ){ $.fancybox.prev(); } else { $.fancybox.next(); } } else if( k == 27 ){ $.fancybox.close(); } } e.stopImmediatePropagation(); });</script>';
      
      box += '  <script type="text/javascript">tagsToDiv( \'' + href + '\' );</script>';
      box += '  <script type="text/javascript">$(\'div.tag_input_outer\').width( $.fancybox.width ); console.log( $.fancybox.width );</script>';
      box += '  <input id="input_tmp_' + href + '" type="text" class="alx_tags alx_tags_tmp" value="" tabindex="-1" />';
      box += '  <div id="div_tags_' + href + '" class="div_tags"><div style="clear:both;"></div></div>';
  
      box += '</div><!-- .tag_input_outer -->';

      // Set input box as fancybox title
      this.title = box;
    } ,
  
    afterLoad : function()
    {
      $('div.tag_input_outer').width( $.fancybox.width ); 
      console.log( $.fancybox.width );
      console.log( $('#fancybox-content').width() );
      console.log( $('#fancybox-inner').width() );

    }
    
  });



});
