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

    //afterLoad : function() 
    beforeLoad : function() 
    {
      // Extract the tags from the title attribute
      var tags = this.title.split(',');

      // Begin markup for input box
      var box = '<input type="text" class="alx_tags" value="';

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
