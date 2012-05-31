<div id="alx_nav">
  <ul>
    <li id="alx_nav_dashboard" style="float:left;">
      <a style="font-family:'Arial',sans-serif;line-height:1.5em;margin:10px;" href="dashboard.php">dashboard</a>
    </li>
    <li id="alx_nav_inbox" style="float:right;">
      <a style="font-family:'Arial',sans-serif;line-height:1.5em;margin:10px;" href="inbox.php">inbox</a>
    </li>
    <li id="alx_nav_search" style="width:400px;margin: 0 auto;">
<?php

$tag_search = '';
if(  isset($_GET['t'])  &&  strlen($_GET['t']) > 0  )
{
  foreach( $tag_names as $t )
  {
    $tag_search .= $t . ", ";
  }
}

?>
      <input id="alx_input_tag_search" type="text" style="width:100%;" 
             value="<?php print $tag_search; ?>" />
      <script>
      
      $('input#alx_input_tag_search').keyup( function(e)
      {
        k = ( e.keyCode ? e.keyCode : e.which );
        if(  k == 13  &&  $(this).val().trim().length > 0  ) 
        {
          var raw = $(this).val().replace(/,?\s?$/,'').split(',');
          var query = new Array;
          for( var i = 0 ; i < raw.length ; i++ )
          {
            query[ query.length ] = raw[i].trim();
          }

          var jqxhr = $.ajax(
          {
            url: "actions/tags/names_to_ids.php" ,
            type: 'POST' ,
            data: { 'names': query }
          })
          .done( function( response )
          {
            window.location = 'gallery.php?t=' + response;
          });
        }
      });

      </script>
    </li>
  </ul>
  <div style="clear:both;"></div>
</div>
