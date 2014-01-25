<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_rubric', 'model_user'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->helper(array('functions', 'text'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/dashboard');
		define('URL_HOME_RUBRIC' , 'admin/dashboard/rubric');
		define('URL_HOME_USERS'  , 'admin/dashboard/users');
		session_start();
		$this->output->enable_profiler(TRUE);
	}

	// Obtenir le login
	private function get_login()
	{
		$data = $this->session->userdata('logged_in');

		return $data['login'];
	}

	// Obtenir le niveau d'accréditation
	private function get_level()
	{
		$data = $this->session->userdata('logged_in');

		return $data['level'];
	}

	// Obtenir la redirection
	private function get_redirect()
	{
		$data = $this->session->set_flashdata('alert', 'Cette page est protégée par un mot de passe.').redirect(base_url('admin'));

		return $data;
	}

	// Afficher tous les articles
	public function index()
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();

			$data['page']  = 'home';
			$data['title'] = 'Ma dashboard';
			$data['query'] = $this->model_content->get_contents();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou éditer un article
	public function edit($c_id = '')
	{
		if ($this->session->userdata('logged_in')):

			// Pour afficher le login
			$data['login'] = $this->get_login();

			// Chargement des rubriques (inputs)
			$data['rubrics'] = $this->model_rubric->get_rubrics();

			// Mise en place du formulaire
			$this->form_validation->set_rules('c_title', 'Titre', 'trim|required|callback_check_content');
			$this->form_validation->set_rules('c_content', 'Contenu', 'trim|required');
			$this->form_validation->set_rules('rubric', 'Rubrique', 'required');

			// Assignations du formulaire
			$c_title   = $this->input->post('c_title');
			$c_content = $this->input->post('c_content');
			$r_id 	   = $this->input->post('rubric');

			// On vérifie si c'est pour ajouter ou modifier via l'URI
			if ($this->uri->total_segments() == 3):
				$data['page']  = 'add_content';
				$data['title'] = 'Ajouter un article';

				// Réécriture du titre pour la future URL de l'article
				$c_url_rw = url_title(convert_accented_characters($c_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_content->create_content($r_id, $c_title, $c_content, $c_url_rw);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" ajouté.');
					redirect(URL_HOME_CONTENT);
				endif;

			else:
				$get_content = $this->model_content->get_content($c_id, '');
				// Vérification de l'id
				if ($get_content->num_rows() == 1):
					$data['page']  	   = 'edit_content';
					$row 			   = $get_content->row();
					$data['r_id']	   = $row->r_id;
					$data['c_title']   = $row->c_title;
					$data['c_content'] = $row->c_content;
					$data['title']	   = 'Modifer l\'article ' . $data['c_title'];

					if ($this->form_validation->run() !== FALSE):
						$this->model_content->update_content($r_id, $c_title, $c_content, $c_id);
						$this->session->set_flashdata('success', 'Article "' . $c_title . '" modifié.');
						redirect(URL_HOME_CONTENT);
					endif;

				else:
					$this->session->set_flashdata('alert', 'Cette article n\'existe pas ou n\'a jamais existé');
					redirect(URL_HOME_CONTENT);
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Vérifier si l'article existe
	public function check_content($c_title)
	{
		if ($this->model_content->get_content('', $c_title)->num_rows() == 1):
			$this->form_validation->set_message('check_content', 'Impossible de rajouter l\'article "' . $c_title . '" car ce dernier existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}


	// Supprimer un article
	public function delete($id = '')
	{
		if ($this->session->userdata('logged_in')):

			// Si l'utilisateur existe, on peut le supprimer
			if ($this->model_content->get_content($id)->num_rows() == 1):
				$this->model_content->delete_content($id);
				$this->session->set_flashdata('success', 'L\'article a bien été supprimé');
				redirect(base_url('admin'));

			// Sinon on affiche le message ci-dessous :
			else:
				$this->session->set_flashdata('alert', 'Cette article n\'existe pour ou n\'a jamais existé');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		else:
			$this->get_redirect();
		endif;
	}

	// Afficher toutes les rubriques
	public function rubric()
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();

			$data['page']  = 'rubric';
			$data['title'] = 'Rubriques';

			$data['query'] = $this->model_rubric->get_rubrics();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou modifier une rubrique
	public function edit_rubric($r_id = '')
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();
			
			// Mise en place du formulaire via form-validation
			$this->form_validation->set_rules('r_title', 'Titre', 'trim|required|callback_check_rubric');
			$this->form_validation->set_rules('r_description', 'Description', 'trim|required');

			// Assignations du formulaire
			$r_title	   = $this->input->post('r_title');
			$r_description = $this->input->post('r_description');

			// On vérifie si c'est pour ajouter ou modifier via l'URI
			if ($this->uri->total_segments() == 3):
				$data['page']  = 'add_rubric';
				$data['title'] = 'Ajouter une rubrique';

				// Réécriture du titre pour la future URL de la rubrique
				$r_url_rw = url_title(convert_accented_characters($r_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_rubric->create_rubric($r_title, $r_description, $r_url_rw);
					$this->session->set_flashdata('success', 'Rubrique "' . $r_title . '" ajoutée');
					redirect(base_url(URL_HOME_RUBRIC));
				endif;

			else:
				$get_content = $this->model_rubric->get_rubric($r_id, '');
				// Vérification de l'id
				if ($get_content->num_rows() == 1):
					$data['page']  		   = 'edit_rubric';
					$data['content']	   = $this->model_content->get_content_by_rubric($r_id);
					$row 		   		   = $get_content->row();
					$data['r_title']	   = $row->r_title;
					$data['r_description'] = $row->r_description;
					$data['title'] 		   = 'Modifier la rubrique ' . $data['r_title'];

					if($this->form_validation->run() !== FALSE):
						$this->model_rubric->update_rubric($r_title, $r_description, $r_id);
						$this->session->set_flashdata('success', 'Catégorie "' . $r_title . '" modifiée.');
						redirect(base_url(URL_HOME_RUBRIC));
					endif;

				else:
					$this->session->set_flashdata('alert', 'Cette rubrique n\'existe pas ou n\'a jamais existé.');
					redirect(URL_HOME_RUBRIC);
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Vérifier si la rubrique existe
	public function check_rubric($r_title)
	{
		if ($this->model_rubric->get_rubric('', $r_title)->num_rows() == 1):
			$this->form_validation->set_message('check_rubric', 'Impossible de rajouter la rubrique "' . $r_title . '" car cette dernière existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Supprimer une catégorie
	public function delete_rubric($r_id)
	{
		if ($this->session->userdata('logged_in')):
			// On vérifie si la rubrique existe toujours
			if ($this->model_rubric->get_rubric($r_id)->num_rows() == 1):

				// On vérifie si il y a des articles rattachés à cette rubrique
				if ($this->model_content->get_content_by_rubric($r_id)->num_rows() == 0):
					$this->model_rubric->delete_rubric($r_id);
					$this->session->set_flashdata('success', 'Rubrique supprimée.');
				else:
					$this->session->set_flashdata('alert', 'Impossible de supprimer cette rubrique car il y a un ou plusieurs article(s) rattaché(s). <a href="'.base_url(URL_HOME_CONTENT . '/edit_rubric/'. $r_id .'#others').'">Afficher</a>');
				endif;

			else:
				$this->session->set_flashdata('alert', 'Cette rubrique n\'existe pas ou n\'a jamais existé.');
			endif;

			redirect(base_url(URL_HOME_RUBRIC));

		else:
			$this->get_redirect();
		endif;
	}

	// Afficher les utilisateurs
	public function users()
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();
			// Pour récupérer le level de l'user
			$data['level'] = $this->get_level();

			$data['page']  = 'users';
			$data['title'] = 'Utilisateurs';

			$data['query'] = $this->model_user->get_users();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou modifier un utilisateur
	public function edit_user($u_id = '')
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();
			// Pour récupérer le level de l'user
			$data['level'] = $this->get_level();

			// Si l'user est bien un admin
			if ($data['level'] == 1):

				// Mise en place du formulaire via form-validation
				$this->form_validation->set_rules('u_login', 'Login', 'trim|required|callback_check_user');
				$this->form_validation->set_rules('u_pass', 'Pass', 'trim|required');
				$this->form_validation->set_rules('u_level', 'Level', 'required');

				// Assignations du formulaire
				$u_login = $this->input->post('u_login');
				$u_pass  = $this->input->post('u_pass');
				$u_level = $this->input->post('u_level');

				// On vérifie si c'est pour ajouter ou modifier via l'URI
				if ($this->uri->total_segments() == 3):
					$data['page']  = 'add_user';
					$data['title'] = 'Ajouter un utilisateur';

					if ($this->form_validation->run() !== FALSE):
						$this->model_user->create_user($u_login, $u_pass, $u_level);
						$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice "' . $u_login . '" ajouté(e)');
						redirect(base_url(URL_HOME_USERS));
					endif;

				else:
					$data['page']  	 = 'edit_user';
					$row 		     = $this->model_user->get_user($u_id)->num_rows();
					$data['u_login'] = $row->u_login;
					$data['u_pass']  = $row->u_pass;
					$data['u_level'] = $row->u_level;
					$data['title']   = 'Mofidifier l\'utilisateur ' . $data['u_login'];

					if ($this->form_validation->run() !== FALSE):
						$this->model_user->update_user($u_login, $u_pass, $u_level, $u_id);
						$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice "' . $u_login . '" modifié(e) (les paramètres prendront effet lors de la prochaine connexion).');
						redirect(base_url(URL_HOME_USERS));
					endif;

				endif;

				$this->load->view(URL_LAYOUT, $data);

			else:
				$this->session->set_flashdata('alert', 'Vous ne disposez pas des droits nécessaires pour accèder à cette partie.');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		else:
			$this->get_redirect();
		endif;
	}

	// Vérifier si l'utilisateur existe
	public function check_user($u_login)
	{
		if ($this->model_user->get_user('', $u_login)->num_rows() == 1):
			$this->form_validation->set_message('check_user', 'Impossible de rajouter l\'utilisateur "' . $u_login . '" car ce dernier existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Supprimer un utilisateur
	public function delete_user($u_id)
	{
		if ($this->session->userdata('logged_in')):
			// Pour récupérer le level de l'utilisateur
			$data['level'] = $this->get_level();

			// Si l'utilisateur est bien un admin
			if ($data['level'] == 1):

				// On vérifie si l'utilisateur existe toujours
				if ($this->model_user->get_user($u_id)->num_rows() == 1):
					$this->model_user->delete_user($u_id);
					$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice supprimé(e).');
				else:
					$this->session->set_flashdata('alert', 'Cette utilisateur n\'existe pas ou n\'a jamais existé.');
				endif;

				redirect(base_url(URL_HOME_USERS));

			else:
				$this->session->set_flashdata('alert', 'Vous ne disposez pas des droits nécessaires pour accèder à cette partie.');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		else:
			$this->get_redirect();
		endif;
	}

}


/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */