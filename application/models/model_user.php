<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model {

	// Vérification pour le login
	function login($login, $password)
	{
		$this->db->select('u_id, u_login, u_pass, u_level')
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

	// Obtenir tous les utilisateur
	function get_users()
	{
		$this->db->select('u_id, u_login, u_pass, u_level, u_biography')
				 ->from('user')
				 ->order_by('u_id', 'ASC');

		$query = $this->db->get();
		return $query;
	}

	// Obtenir un utilisateur
	function get_user($u_id, $u_login)
	{
		$this->db->select('u_id, u_login, u_pass, u_level, u_biography');
		$this->db->from('user');
		if (empty($u_login)):
		$this->db->where('u_id', $u_id);
		else:
		$this->db->where('u_login', $u_login);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	// Vérifie si l'utilisateur existe déjà
	function check_user($u_id, $u_login)
	{
		$this->db->select('u_login')
				 ->from('user')
				 ->where('u_id <>', $u_id)
				 ->where('u_login', $u_login);

		$query = $this->db->get();
		return $query;
	}

	// Vérifier le mot de l'utilisateur
	function check_user_password($u_id, $u_old_pass)
	{
		$this->db->select('u_pass')
				 ->from('user')
				 ->where('u_id', $u_id)
				 ->where('u_pass', md5($u_old_pass));

		$query = $this->db->get();
		return $query;
	}

	// Créer un utilisateur
	function create_user($u_login, $u_pass, $u_level, $u_biography)
	{
		$data = array(
			'u_login'	  => $u_login,
			'u_pass'	  => md5($u_pass),
			'u_level'	  => $u_level,
			'u_biography' => $u_biography
		);

		$this->db->insert('user', $data);
	}

	// Mettre à jour un utilisateur
	function update_user($u_login, $u_level, $u_biography, $u_id)
	{
		$data = array(
			'u_login' 	  => $u_login,
			'u_level' 	  => $u_level,
			'u_biography' => $u_biography
		);

		$this->db->where('u_id', $u_id);
		$this->db->update('user', $data);
	}

	// Supprimer un utilisateur
	function delete_user($u_id)
	{
		$this->db->where('u_id', $u_id)
				 ->delete('user');
	}

	// Changement de mot de passe
	function update_password_user($u_new_pass, $u_id)
	{
		$data = array(
			'u_pass' => md5($u_new_pass)
		);

		$this->db->where('u_id', $u_id);
		$this->db->update('user', $data);
	}

}


/* End of file model_user.php */
/* Location: ./application/models/admin/model_user.php */