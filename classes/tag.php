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
      $id = substr($id,1);

      $this->include = false;
    }
    else
    {
      $this->include = true;
    }

    if( is_numeric( $id ) )
    {
      $this->id = $id;
     
      $this->name = $this->getName();
    }
    else
    {
      $this->name = $id;

      $this->id = $this->getId();
    }



    $this->items = $this->getItems();

debug( $this );
  }


  
  function getName()
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



  function getId()
  {
    $cnxn = openMySQL();

    $q = "SELECT tags.id
          FROM tags
          WHERE tags.name='{$this->name}'";
    $r = mysql_query( $q , $cnxn );
    $s = mysql_fetch_assoc($r);
    
    closeMySQL( $cnxn );

    return $s['id'];    
  }



  function getItems()
  {
    $cnxn = openMySQL();

    $return_array = array();
  
    return $return_array;

    closeMySQL( $cnxn );
  }



}
