<?php

class AlexandriaItem
{
  public $id;
  public $type;
  public $src;
  public $tag_ids;
  public $tag_names;


  
  function __construct( $id , $type , $tag=true )
  {
    $this->id = $id;

    $this->type = $type;
   
    $this->src = FS_ROOT . "stacks/" . $this->getPath( true ) . "." . $this->type;

    if( $tag == true )
    {
      $tags = $this->getTags();

      $this->tag_ids = array();
      $this->tag_names = array();
      foreach( $tags as $t )
      {
        $this->tag_ids[] = $t['id'];

        $this->tag_names[] = $t['name'];
      }
    }
debug( $this );
  }



  function getPath( $array=false )
  {
    $id_array = str_split( $this->id );

    $path_string = '';
    $path_array = array();
    for( $i=0 ; $i < count( $id_array ) ; $i++ )
    {
      $path_string .= $id_array[$i];

      if( ($i+1) % 4 == 0 )
      {
        $path_array[] = $path_string;
        $path_string = '';
      }
    }

    return $array == false ? $path_array : implode( '/' , $path_array );
  }



  function getTags()
  {
    $cnxn = openMySQL();

    $q = "SELECT t.id , t.name
          FROM items_to_tags AS i2t
            LEFT JOIN tags AS t
              ON i2t.tag_id = t.id
          WHERE i2t.item_id = '{$this->id}'
          ORDER BY t.name";
    $r = mysql_query( $q , $cnxn );
    $return_tags = array();
    while( $s = mysql_fetch_assoc( $r ) )
    {
      $return_tags[] = array( 'id'=>$s['id'] , 'name'=>$s['name'] );
    }

    closeMySQL( $cnxn );

    return $return_tags;
  }


}

?>
