$(document).ready( function()
{
  console.log( "JavaScript Loaded" );
  $("a[rel=gallery]").fancybox(
  {
    'transitionIn' : 'elastic',
    'transitionOut' : 'elastic'		
  });
});
