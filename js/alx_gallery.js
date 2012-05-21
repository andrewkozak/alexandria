$(document).ready( function()
{

  //if( $(window).width % 2 == 0 )
  //{
    resetThumbSize();
  //}

  $(window).resize( function()
  {
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
      var box = '<script type="text/javascript">$(\'input#input_' + href + '\').keyup( function(e){ k = ( e.keyCode ? e.keyCode : e.which ); storeChangedTags(\'' + href + '\'); e.stopImmediatePropagation(); });</script> <input id="input_' + href + '" type="text" class="alx_tags" value="';
      
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
      box += '" />';

      // Set input box as fancybox title
      this.title = box;
    }
  });
});
