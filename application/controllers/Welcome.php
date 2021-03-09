<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pagesmodel');
	}
	public function index()
	{
		$data['tables'] = $this->db->list_tables();

		$this->load->view('generator/index',$data);
	}
	public function table_data($table)
	{
		$data['fields'] = $this->db->field_data($table);

		
		$view_file = $this->load->view('generator/tables', $data, TRUE);

		echo($view_file);

	}
	public function generate($table_name)
	{
		$data['fields'] = $this->db->field_data($table_name);
		$model_name = $this->generate_model_name($table_name);

		// print_r($this->generate_construct($fields));
		$this->generate_view_add($data,$table_name);
		// $this->generate_edit($table_name);
		// $this->generate_models($table_name);


	}

	private function generate_index($table_name)
	{
		$word =  "#data['".$table_name."s'] = #this->".$this->generate_model_name($table_name)."->get_all();\n #this->load->view('".$table_name."/index',#data);";
		echo($this->removeHash($word));
	}
	private function generate_construct($data)
	{
		$model_name = $data['model_name'];
		$constr = "public function __construct()";
		$constr .="{";
		$constr .="\n parent::__construct();";
		$constr .="#this->load->model('".$model_name."');";
		$constr .="#this->load->model('session');";

		
		$constr .="\n };";
		return $this->removeHash($constr);
	}

	private function generate_update($data,$table_name)
	{

		// $arrayName = array('' => , );
		$word = "#".$table_name."data = \n array( \n";
		$word .= $this->insertsStatement($data);
        $word .= "
    	#this->".$table_name."data->update(#".$table_name.", #id); \n
        #this->session->set_flashdata('success', '".$table_name." updated Successfully'); \n
        redirect('".$table_name."');
		";
		echo($this->removeHash($word));

	}

	private function generate_edit($table_name)
	{

		$word =  "#data['".$table_name."s'] = #this->".$this->generate_model_name($table_name)."->get_item(#id);\n #this->load->view('".$table_name."/edit',#data);";
		echo($this->removeHash($word));

	}

	private function generate_delete($data,$table_name)
	{
		$word = "#this->".$this->generate_model_name($table_name)."->delete(#id);
        #this->session->set_flashdata('success', '".$table_name." deleted');
        redirect('".$table_name."');";
	}

	private function generate_add($data,$table_name)
	{

		$word =  "#this->load->view('".$table_name."/add',#data);";
		echo($this->removeHash($word));

	}
	public function insertsStatement($data)
	{
		$inserts = "";
		foreach ($data['fields'] as $dat) { //.$dat->name.
			if ($dat->primary_key) {
				# code...
			}else{

			$inserts .= "'".$dat->name."'=>#this->input->post('".$dat->name."'), \n";
			}
		};
		return $inserts;

	}

	private function generate_store($data,$table_name)
	{

		// $arrayName = array('' => , );
		$word = "#".$table_name."data = \n array( \n";
		$word .= $this->insertsStatement($data);
        $word .= "
    	#this->".$table_name."data->insert(#".$table_name."); \n
        #this->session->set_flashdata('success', '".$table_name." added Successfully'); \n
        redirect('".$table_name."');
		";
		echo($this->removeHash($word));
	}

	private function generate_models($table_name)
	{

		// $arrayName = array('' => , );
		$word = "
		class ".$this->generate_model_name($table_name)." extends CI_Model {
			\n
		function get_item(#id){
			\n
			return #this->db->get_where('".$table_name."', array('id' => #id));

		}
		\n
		public function store(#params)
		{
			return #this->db->insert('".$table_name."', #params);
		}
		\n

		public function update(#params, #id)
		{
		#this->db->where('id', #id);
        #this->db->update('".$table_name."', #params);
			return TRUE;
		}
		\n
		function get_all(){
			\n
			return #this->db->get('".$table_name."')->result_array();
		}
		function delete(#id){
			\n
			
		#this->db->where('id', #id); \n
		return #this->db->delete('Table');

		}
		\n
		}

		\n
		";
		echo($this->removeHash($word));
	}

	private function generate_model_name($table_name)
	{

		return ucfirst($table_name."_model");
	}

	private function removeHash($str)
	{
		return str_replace("#", "$", $str) ;
	}
	private function removeUnder($str)
	{
		 return str_replace("_", " ", $str);
	}
	// views

	private function generate_view_home($data, $table_name)
	{
		$view = "
		<table class='table'>
			<thead>
				<tr>";
				$view .= "<th>no.</th>";
				foreach ($data['fields'] as $field) {
					$view .="<th>".$field->name."</th>";
				}
				
				$view .="
				</tr>
			</thead>
			<tbody>
			foreach (#".$table_name."s as &index => #".$table_name.") {
				<tr>
				<td>&lt;?php echo #index + 1; ?&gt;</td>
				";
				foreach ($data['fields'] as $field) {
					$view .="<td> &lt;?php echo #".$table_name."['".$field->name."']; ?&gt;</td>";
				}
				$view .="</tr>
			}
			</tbody>
		</table>

		";
		// $view = str_replace("<=","php",$view);
		echo($this->removeUnder($this->removeHash($view)));
	}

	private function generate_view_add($data, $table_name)
	{
		$view = "<form action=".base_url().$table_name." method='post'>";
		foreach ($data['fields'] as $key => $fields) {
			if ($fields->primary_key) {
				# code...
			}else{
			# code...
		$view .= "<div class='form-group'>
        <label for='".$fields->name."'>".$fields->name.":</label>
        <input type='text' class='form-control' id='".$fields->name."' placceholder='".$fields->name."' name='".$fields->name."'>
      </div>";

		}
		}
		$view .= "</form>";

		echo($this->removeUnder($this->removeHash($view)));
	}

	private function generate_view_update($data, $table_name)
	{
		
		$view = "<form action=".base_url().$table_name." method='post'>";
		foreach ($data['fields'] as $key => $fields) {
			if ($fields->primary_key) {
				# code...
			}else{
			# code...
		$view .= "<div class='form-group'>
        <label for='".$fields->name."'>".$fields->name.":</label>
        <input type='text' class='form-control' id='".$fields->name."' placceholder='".$fields->name."' name='".$fields->name."'>
      </div>";

		}
		}
		$view .= "</form>";

		echo($this->removeUnder($this->removeHash($view)));
	}

}
