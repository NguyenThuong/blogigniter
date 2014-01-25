<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_content extends CI_Model {

	// Lire tous les articles
	function get_contents()
	{
		$this->db->select('c_id, c_title, c_content, c_cdate, c_udate, c_url_rw, r_title, r_url_rw')
				 ->from('content')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	// Lire un article
	function get_content($c_id, $c_title)
	{
		$this->db->select('c_id, content.r_id, c_title, c_content');
		$this->db->from('content');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		if (empty($c_title)):
		$this->db->where('c_id', $c_id);
		else:
		$this->db->where('c_title', $c_title);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	// Créer un article
	function create_content($r_id, $c_title, $c_content, $c_url_rw)
	{
		$date = new DateTime(null, new DateTimeZone('Europe/Paris'));
		$data = array(
			'r_id'  	=> $r_id,
			'c_title' 	=> $c_title,
			'c_content' => $c_content,
			'c_cdate' 	=> $date->format('Y-m-d H:i:s'),
			'c_udate'   => $date->format('Y-m-d H:i:s'),
			'c_url_rw' 	=> $c_url_rw,
		);

		$this->db->insert('content', $data);
	}
	

	// Mettre à jour un article
	function update_content($r_id, $c_title, $c_content, $c_id)
	{
		$date = new DateTime(null, new DateTimeZone('Europe/Paris'));
		$data = array(
			'r_id'  	=> $r_id,
			'c_title' 	=> $c_title,
			'c_content' => $c_content,
			'c_udate'   => $date->format('Y-m-d H:i:s'),
		);

		$this->db->where('c_id', $c_id);
		$this->db->update('content', $data);
	}

	// Supprimer un article :
	function delete_content($c_id)
	{
		$this->db->where('c_id', $c_id)
				 ->delete('content'); 
	}


	// Obtenir les articles pour le listing (pagination)
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
	function get_content_by_slug($slug_rubric, $slug_content)
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

	// Le contenu dans une rubrique spécifique :
	function get_content_by_rubric($r_id)
	{
		$this->db->select('c_id, c_title')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->where('rubric.r_id', $r_id);

		$query = $this->db->get();
		return $query;
	}


}

/* End of file model_admin.php */
/* Location: ./application/models/admin/model_admin.php */