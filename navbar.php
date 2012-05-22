<div id="global_navbar">
  <ul>
    <li id="nav_dashboard" style="float:left;">
      <a style="font-family:'Arial',sans-serif;line-height:1.5em;margin:10px;" href="dashboard.php">dashboard</a>
    </li>
    <li id="nav_inbox" style="float:right;">
      <a style="font-family:'Arial',sans-serif;line-height:1.5em;margin:10px;" href="inbox.php">inbox</a>
    </li>
    <li id="nav_search" style="width:400px;margin: 0 auto;">
<?php

$tag_search = '';
if( $_GET['t'] )
{
  foreach( $tag_names as $t )
  {
    $tag_search .= $t . ", ";
  }
}

?>
      <input id="tag_search" type="text" style="width:100%;" value="<?php print $tag_search; ?>" />
      <script>
      
      $('input#tag_search').keyup( function(e)
      {
        k = ( e.keyCode ? e.keyCode : e.which );
        if( k == 13 )
        {
console.log( $(this).val() );
          var tag_query = new Array;
          var tag_query_raw = $(this).val().replace(/,?\s?$/ , '').split(',');
          for( var i = 0 ; i < tag_query_raw.length ; i++ )
          {
            tag_query[ tag_query.length ] = tag_query_raw[i].trim();
          }
console.log( tag_query );

          var jqxhr = $.ajax(
          {
            url: "actions/tags/names_to_ids.php" ,
            type: 'POST' ,
            data: { 'names': tag_query }
          })
          .done( function( response )
          {
            window.location = 'gallery.php?t=' + response;
          })
          .fail( function() 
          {
            console.log( "AJAX error" ); 
          })
          .always( function() 
          {
            //do something;
          });
        }
      });

      </script>
    </li>
  </ul>
  <div style="clear:both;"></div>
</div>
