<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model {

	function login($login, $password)
	{
		$this->db->select('*')
				 ->from('user')
				 ->where('u_login', $login)
				 ->where('u_pass', MD5($password))
				 ->limit(1);
		$query = $this->db->get();

		if($query->num_rows() == 1):
			return $query->result();
		else:
			return false;
		endif;
	}
	
}


/* End of file model_user.php */
/* Location: ./application/models/admin/model_user.php */