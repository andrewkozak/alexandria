$(document).ready( function()
{
  $("a[rel=gallery]").fancybox(
  {
    'transitionIn' : 'elastic',
    'transitionOut' : 'elastic'		
  });

  $('.fancybox-buttons').fancybox(
  {
    openEffect  : 'none',
    closeEffect : 'none',
    
    prevEffect : 'none',
    nextEffect : 'none',

    closeBtn  : false,

    helpers : 
    {
      title : 
      {
	type : 'inside'
      },
      buttons : {}
    },

    beforeLoad : function() 
    {
      // Extract the ID from the href attribute
      var href = this.href.replace( fs_root + 'stacks/' , '' ).replace( /\..{0,5}$/i , '' );
      while( href.match('/') ){ href = href.replace( '/' , '' ); }

      // Extract the tags from the title attribute
      //var tags = this.title.split(',');
      var tags = $('div.tags#tags_' + href ).html().split(',');

      // Begin markup for input box
      //var box = '<input type="text" class="alx_tags" value="';
      var box = '<script type="text/javascript">$(\'input#input_' + href + '\').keypress( function(e){ k = ( e.keyCode ? e.keyCode : e.which ); if( k == 13 ){ submitTags(\'' + href + '\'); } e.stopImmediatePropagation(); });</script> <input id="input_' + href + '" type="text" class="alx_tags" value="';
      
      // Put each tag in input box
      for( var i = 0 ; i < tags.length ; i++ )
      {
        box += tags[i] + ', ';
      }

      // Close markup for input box
      box += '" />';

      // Set input box as fancybox title
      this.title = box;
    }
  });
});
