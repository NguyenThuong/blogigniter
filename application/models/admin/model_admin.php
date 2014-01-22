<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_admin extends CI_Model {

	// Create : Créer un article
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
	
	// Read : Lire tous les articles
	function read_content()
	{
		$this->db->select('c_id, c_title, c_content, c_cdate, c_udate, c_url_rw, r_title, r_url_rw')
				 ->from('content')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	// Lire un article en particulier :
	function get_content($id)
	{
		$this->db->select('c_id, content.r_id, c_title, c_content')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->where('c_id', $id)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	// Le contenu dans une rubrique spécifique :
	function get_content_by_rubric($id)
	{
		$this->db->select('c_id')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->where('rubric.r_id', $id);

		$query = $this->db->get();
		return $query;
	}


	// Update : mettre à jour un article
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

	// Delete : supprimer un article :
	function delete_content($id)
	{
		$this->db->where('c_id', $id)
				 ->delete('content'); 
	}

	// Create : créer une rubrique
	function create_rubric($r_title, $r_description, $r_url_rw)
	{
		$data = array(
			'r_title'       => $r_title,
			'r_description' => $r_description,
			'r_url_rw'      => $r_url_rw
		);

		$this->db->insert('rubric', $data);
	}

	// Read : Lire toutes les rubriques
	function read_rubric()
	{
		$this->db->select('r_id, r_title, r_description')
				 ->from('rubric')
				 ->order_by('r_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}
	// Lire une rubrique en particulier
	function get_rubric($id)
	{
		$this->db->select('r_title, r_description')
				 ->from('rubric')
				 ->where('r_id', $id)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	// Update : mettre à jour une rubrique
	function update_rubric($r_title, $r_description, $r_id)
	{
		$data = array(
			'r_title'		=> $r_title,
			'r_description' => $r_description
		);

		$this->db->where('r_id', $r_id);
		$this->db->update('rubric', $data);
	}

	// Delete :  supprimer une rubrique
	function delete_rubric($id)
	{
		$this->db->where('r_id', $id)
				 ->delete('rubric');
	}

}

/* End of file model_admin.php */
/* Location: ./application/models/admin/model_admin.php */