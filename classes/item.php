<?php

class AlexandriaItem
{
  public $id;
  public $type;
  public $src;
  public $tag_ids;
  public $tag_names;



  function __construct( $id=null )
  {
    if( $id != null )
    {
      $cnxn = openMySQL();

      $q = "SELECT COUNT(*)
            FROM items
            WHERE items.id='{$id}'
            LIMIT 1";
      $r = mysql_query( $q , $cnxn );
      $s = mysql_fetch_assoc( $r );
 
      closeMySQL( $cnxn );
      
      if( $s['COUNT(*)'] > 0 )
      {
        $this->id = $id;
      }
      else{ return false; }
    }
  }



  function getItemType( $force=false )
  {
    if( !isset($this->id) ){ return false; }

    if(  !isset($this->type)  ||  $force == true  )
    {
      $cnxn = openMySQL();
 
      $q = "SELECT `type`
            FROM items
            WHERE `id`='{$this->id}'
            LIMIT 1";
      $r = mysql_query( $q , $cnxn );
      $s = mysql_fetch_assoc( $r );

      closeMySQL( $cnxn );
   
      if(  isset( $s['type'] )  &&  strlen($s['type']) > 0  )
      {
        $this->type = $s['type'];
      }
      else{ return false; }
    }
      
    return $this->type;
  }
  


  function getItemSrc( $force=false )
  {
    if( !isset($this->id) ){ return false; }

    if(  !isset($this->src)  ||  $force == true  )
    {
      if( $this->getItemType() == false ){ return false; }
      
      $this->src = WWW_ROOT . "stacks/" . $this->getPath( true ) . "." . $this->type;
    }

    return $this->src;
  }
 
  

  function clearItemTags()
  {
    if( !isset($this->id) ){ return false; }

    $this->tags = array();
    $this->tag_ids = array();
    $this->tag_names = array();

    $cnxn = openMySQL();

    $q = "DELETE FROM items_to_tags 
          WHERE items_to_tags.item_id = '{$this->id}'";
    mysql_query( $q , $cnxn );

    closeMySQL( $cnxn );

    return;  
  }

 

  function getItemTags( $force=false )
  {
    if( !isset($this->id) ){ return false; }

    if(  !isset($this->tags)  ||  $force == true  )
    {
      $cnxn = openMySQL();
   
      $q = "SELECT t.id AS tag_id , 
                   t.name AS tag_name
            FROM items AS i
              JOIN items_to_tags AS i2t
                ON i.id = i2t.item_id
              JOIN tags AS t
                ON i2t.tag_id = t.id
            WHERE i.id = '{$this->id}'
            ORDER BY t.name";
      $r = mysql_query( $q , $cnxn );
      $this->tags = array();
      $this->tag_ids = array();
      $this->tag_names = array();
      while( $s = mysql_fetch_assoc($r) ) 
      {
        $this->tags[] = array( 'id'=>$s['tag_id'] , 'name'=>$s['tag_name'] );
        $this->tag_ids[] = $s['tag_id'];
        $this->tag_names[] = $s['tag_name'];
      }      

      closeMySQL( $cnxn );
      
      sort( $this->tag_ids );
      sort( $this->tag_names );
    }
   
    return $this->tags;
  }



  function getItemTagIds( $force=false )
  {
    if( !isset($this->id) ){ return false; }

    if(  !isset($this->tag_ids)  ||  $force == true  )
    {
      if( $this->getItemTags() == false ){ return false; }
    }

    return $this->tag_ids;
  }



  function getItemTagNames( $force=false )
  {
    if( !isset($this->id) ){ return false; }

    if(  !isset($this->tag_names)  ||  $force == true  )
    {
      if( $this->getItemTags() == false ){ return false; }
    }

    return $this->tag_names;
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



  function setItemTags( $tags , $type=null )
  {
    $this->clearItemTags();
   
    if( strlen( trim($tags) ) > 0 )
    { 
      $new_tags = explode( ',' , $tags );
    
      if( count($new_tags) > 0 )
      { 
        if(  $type != 'id'  &&  $type != 'name'  )
        {
          $all_numbers = true;

          foreach( $new_tags as $nt )
          {
            if( $all_numbers == true )
            {
              if( !is_numeric($nt) )
              {
                $all_numbers = false;
              }          
            }
          }
        
          $type = $all_numbers == true ? 'id' : 'name';
        }

        $cnxn = openMySQL();
 
        $mysql_tags = array();
        if( $type == 'name' )
        {
          foreach( $new_tags as $nt )
          {
            $q = "SELECT COUNT(*)
                  FROM tags
                  WHERE tags.name='{$nt}'";
            $r = mysql_query( $q , $cnxn );
            $s = mysql_fetch_assoc( $r );
          
            if( $s['COUNT(*)'] == 0 )
            {
              $t = "INSERT INTO tags ( `name` )
                    VALUES ( '{$nt}' )";
              mysql_query( $t , $cnxn );
            }
          
            $w = "SELECT tags.id
                  FROM tags
                  WHERE tags.name='{$nt}'";
            $x = mysql_query( $w , $cnxn ); 
            $y = mysql_fetch_assoc( $x );
          
            $mysql_tags[] = $y['id'];
          }
        }
        else if( $type == 'id' )
        {
          $mysql_tags = $new_tags;
        }
  
        sort( $mysql_tags );
        $mysql_tags = array_unique( $mysql_tags );

        foreach( $mysql_tags as $mt )
        {
          $q = "INSERT INTO items_to_tags ( `item_id` , `tag_id` )
                VALUES ( '{$this->id}' , '{$mt}' )";
          mysql_query( $q , $cnxn );
        } 

        closeMySQL( $cnxn );
  
        $this->getItemTags(true);
      }
    }
  
    return;
  }



}

?>
