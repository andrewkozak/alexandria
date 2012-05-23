<?php

class AlexandriaTag
{
  public $id;
  public $name;
  public $items;
  public $include;

 

  function __construct( $id )
  {
    if( substr($id,0,1) == '-' )
    {
      $this->id = substr($id,1);
      
      $this->include = false;
    }
    else
    {
      $this->id = $id;
      
      $this->include = true;
    }

    $this->name = $this->getTagName();

    $this->items = $this->getItems();

debug( $this );
  }


  
  function getTagName()
  {
    $cnxn = openMySQL();

    $q = "SELECT tags.name
          FROM tags
          WHERE tags.id='{$this->id}'";
    $r = mysql_query( $q , $cnxn );
    $s = mysql_fetch_assoc($r);
    
    closeMySQL( $cnxn );

    return $s['name'];    
  }



  function getItems()
  {
    $cnxn = openMySQL();

    $return_array = array();
  
    return $return_array;

    closeMySQL( $cnxn );
  }



}
