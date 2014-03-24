<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_search extends CI_Model {

	// Obtenir tous les mots
	function get_words()
	{
		$this->db->select('s_id, s_word, s_date, s_score')
				 ->from('search')
				 ->order_by('s_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function distinct_words()
	{
		$this->db->distinct()
				 ->select('s_word')
				 ->from('search');

		$query = $this->db->get();
		return $query;
	}

	// Pour la recherche
	function get_research($research, $numero_page, $per_page)
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->like('c_title', $research);
		$this->db->or_like('c_content', $research);
		$this->db->where('c_state', 1);
		$this->db->order_by('c_cdate', 'DESC');

		if ($numero_page and $per_page):
			$this->db->limit($per_page, ($numero_page-1) * $per_page);
		elseif($per_page):
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	}

	// Insérer un mot recherché
	function insert_search($s_word, $s_score)
	{
		$data = array(
			's_word'  => $s_word,
			's_date'  => unix_to_human(now(), TRUE, 'eu'),
			's_score' => $s_score
		);

		$this->db->insert('search', $data);

	}

}


/* End of file model_user.php */
/* Location: ./application/models/admin/model_user.php */