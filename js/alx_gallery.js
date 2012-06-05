$(document).ready( function()
{
  if( $('input#alx_input_tag_search').val().length != 0 )
  {
    $('input#alx_input_tag_search').focus();
    $('input#alx_input_tag_search').val( $('input#alx_input_tag_search').val() );
  }
  
  resetThumbSize();

  $(window).resize( function()
  {
    resetThumbSize();
  });

  $("a[rel=alexandria]").fancybox(
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
      title : { type : 'inside' } ,
      buttons : {}
    } ,
   
    onUpdate : function()
    {
      var href = this.href.replace( www_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }
      
      $('input#alx_input_tmp_'+href).focus();
      $('input#alx_input_tmp_'+href).val( $('input#alx_input_tmp_'+href).val() );
    } ,
 
    beforeClose : function()
    {
      var href = this.href.replace( www_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }
    } ,
    
    beforeLoad : function() 
    {
      var href = this.href.replace( www_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }

      var title_html = '';

      var jqhxr = $.ajax(
      {
        url: "template_image.php" ,
        type: 'POST' ,
        async: false ,
        data: { 'item_id' : href }
      })
      .done( function( response )
      {
        title_html = response;
      });

      this.title = title_html;
    }
  });
});
