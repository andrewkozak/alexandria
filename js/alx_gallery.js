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

    afterLoad : function() 
    {
      var tags = this.title.split(',');

      var box = '<input type="text" class="alx_tags" value="';

for( var i = 0 ; i < tags.length ; i++ )
{
  console.log( tags[i] );

  box += tags[i] + ', ';
}
      box += '" />';

console.log( box );

      this.title = box;
    }
  });
});
