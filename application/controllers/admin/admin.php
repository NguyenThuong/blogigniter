<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('model_user');
		$this->load->library(array('encrypt','session'));
		$this->load->helper(array('functions', 'url'));

		session_start();
	}

	function index()
	{
		if(!$this->session->userdata('logged_in')):
			$this->load->library('form_validation');

			// Mise en place du formulaire
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

			// Si le formulaira n'est pas bon
			if($this->form_validation->run() == FALSE):
				$data['title'] = 'Connexion';
				$this->load->view('admin/view_form_login', $data);
			else:
				$this->session->set_flashdata('success', 'Bienvenue sur votre dashboard.');
				// Redirection vers le dashboard
				redirect(base_url('admin/dashboard'));
				//echo 'ok';
			endif;

		elseif($this->session->userdata('logged_in')):
			redirect(base_url('admin/dashboard'));
		echo 'ok';

		endif;
	}

	// Vérification login / mot de passe dans la BDD
	function check_database($password)
	{

		$login = $this->input->post('username');
		$query = $this->model_user->login($login, $password);

		if($query):
			$sess_array = array();
			foreach($query as $row):
				$sess_array = array(
					'id'    => $row->u_id,
					'login' => $row->u_login,
					'level' => $row->u_level
				);
				$u_id = $row->u_id;
				// Création de la session
				$this->session->set_userdata('logged_in', $sess_array);
			endforeach;
			return TRUE;

		else:
			$this->form_validation->set_message('check_database', 'Login ou mot de passe incorrect');
			return FALSE;

		endif;
	}

	// Déconnexion du dashboard
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->set_flashdata('success', 'Vous êtes désormais déconnecté(e).');
		session_destroy();
		redirect(base_url('admin'), 'refresh');
	}

}


/* End of file admin.php */
/* Location: ./application/controllers/admin/admin.php */