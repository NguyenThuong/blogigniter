<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_feed extends CI_Model{

	function getRecentPosts()
	{
		$this->db->select('c_title, c_content, c_cdate, c_url_rw, r_title, r_description, r_url_rw')
				 ->from('content')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->order_by('c_id', 'DESC')
				 ->limit(10);

		$query = $this->db->get();
		return $query;
	}

}


/* End of file model_feed.php */
/* Location: ./application/models/front/model_feed.php */