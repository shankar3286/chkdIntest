<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_import extends CI_Controller
{
 public function __construct()
 {
  parent::__construct();
  $this->load->model('excel_import_model');
  $this->load->library('excel');
 }

 public function index()
 {
	$this->load->view('welcome_message');
	$this->load->view('excel_import');

 }

 public function import()
 {
  if(isset($_FILES["file"]["name"]))
  {
   $path = $_FILES["file"]["tmp_name"];
   $object = PHPExcel_IOFactory::load($path);
   foreach($object->getWorksheetIterator() as $worksheet)
   {
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    for($row=2; $row<=$highestRow; $row++)
    {
	  $full_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
     $phone_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
     $email = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
     $company = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
	 $designation = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
     $import_data[] = array('full_name'=>$full_name,
                     'phone_number'=>$phone_number,
                     'email'=>$email,
                     'company'=>$company,
                     'designation'=>$designation);
    }
   }
   // print_r($import_data);
   $data=$this->excel_import_model->insert("employee_data",$import_data);
   if($data){
   	echo true;
   }else{
	   echo false;
   }
   			
  } 
 }
 
 public function fetch_employee(){  
   $this->load->model("employee");  
   $fetch_data = $this->employee->make_datatables();  
   $data = array();  
   foreach($fetch_data as $row)  
   {  
        $sub_array = array();  
        $sub_array[] = $row->full_name;  
        $sub_array[] = $row->phone_number;  
        $sub_array[] = $row->email;  
        $sub_array[] = $row->company;
        $sub_array[] = $row->designation;  
        $data[] = $sub_array;  
   }  
   $output = array(  
        "draw"=>intval($_POST["draw"]),  
        "recordsTotal" =>$this->employee->get_all_data(),  
        "recordsFiltered"=>$this->employee->get_filtered_data(),  
        "data"=>$data  
   );  
   echo json_encode($output);  
}  
}

?>
