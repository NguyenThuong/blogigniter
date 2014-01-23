<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_blog extends CI_Model {
	
	// Obtenir tous les articles
	function get_all_contents()
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_url_rw')
				 ->from('content')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	// Obtenir les articles pour le listing
	function get_contents_listing($numero_page, $per_page)
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->order_by('c_id', 'DESC');
		if($numero_page):
			$this->db->limit($per_page, ($numero_page-1) * $per_page);
		else:
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	}

	// Obtenir un article en particulier (via son slug) pour le content
	function get_content($slug_rubric, $slug_content)
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_url_rw')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->where('r_url_rw', $slug_rubric)
				 ->where('c_url_rw', $slug_content);

		$query = $this->db->get();
		return $query;
	}

	// Obtenir les autres articles
	function get_contents_others($slug_content)
	{
		$this->db->select('c_title, c_url_rw, r_url_rw')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->from('content')
				 ->where('c_url_rw <>', $slug_content)
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	// Obtenir les autres articles de la même rubrique
	function get_contents_same_rubric($slug_rubric, $slug_content)
	{
		$this->db->select('c_title, c_url_rw, r_url_rw')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->from('content')
				 ->where('rubric.r_url_rw', $slug_rubric)
				 ->where('content.c_url_rw <>', $slug_content)
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	} 

	// Obtenir toutes les rubriques
	function get_all_rubrics()
	{
		
/*		$query_distinct = $distinct->db->get();
		var_dump($query_distinct);*/
		
		$get_rubrics = $this->db->distinct()->select('rubric.r_id')->join('content', 'content.r_id = rubric.r_id')->from('rubric')->get();
		foreach ($get_rubrics->result() as $row) {
			//$r_id[0] = 'r_id = ' . $row->r_id[0] . ' OR ';
			$r_id[] = ' OR r_id = '. $row->r_id;
			$r_id[0] = current($r_id);
		
		}
		$first    = current($r_id);
		$explode  = explode(" OR", $first);
		$first_id = $explode[1];
		$params   = implode('', $r_id );

		$query = $this->db->query('SELECT r_title, r_url_rw FROM rubric WHERE ' . $first_id . ' ' . $params . ' ORDER BY r_title ASC');
		return $query;
	}

	// Obtenir les rubriques pour le listing (via son slug)
	function get_contents_rubric_listing($slug_rubric, $numero_page, $per_page)
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_description, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->where('rubric.r_url_rw', $slug_rubric);
		$this->db->order_by('content.c_id', 'DESC');
		if($numero_page and $per_page):
			$this->db->limit($per_page, ($numero_page-1) * $per_page);
		elseif($per_page):
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	} 


}

/* End of file model_blog.php */
/* Location: ./application/models/admin/model_blog.php */