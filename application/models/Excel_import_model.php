<?php
class Excel_import_model extends CI_Model
{
 function insert($table_name,$data)
 {
 $result=$this->db->insert_batch($table_name, $data);
  if( $result){
	  return true;
  }else{
	  return false;
  }
 }
}
