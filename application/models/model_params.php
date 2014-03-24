<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_params extends CI_Model {

	// Lire tous les paramètres
	function get_params()
	{
		$this->db->select('p_title, p_about, p_m_description')
				 ->from('params')
				 ->where('p_id', 0);

		$query = $this->db->get();
		return $query;
	}

	// Insérer les paramètres (1ère fois)
	function insert_params($p_title, $p_m_description, $p_about)
	{
		$data = array(
			'p_id'			  => 0,
			'p_title'		  => $p_title,
			'p_m_description' => $p_m_description,
			'p_about'		  => $p_about
		);

		$this->db->insert('params', $data);
	}

	// Mettre à jour les paramètres
	function update_params($p_title, $p_m_description, $p_about)
	{
		$data = array(
			'p_title'		  => $p_title,
			'p_m_description' => $p_m_description,
			'p_about'		  => $p_about
		);

		$this->db->where('p_id', 0);
		$this->db->update('params', $data);
	}


}


/* End of file model_admin.php */
/* Location: ./application/models/admin/model_admin.php */