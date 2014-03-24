<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_rubric', 'model_user', 'model_comment', 'model_params', 'model_search'));
		$this->load->library(array('form_validation', 'session'));
		$this->load->helper(array('form', 'functions', 'text', 'file'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/dashboard');
		define('URL_HOME_RUBRIC' , 'admin/dashboard/rubric');
		define('URL_HOME_USERS'  , 'admin/dashboard/users');
		define('URL_HOME_COM'   , 'admin/dashboard/comment');
		session_start();
		$this->output->enable_profiler(TRUE);
	}


	// Afficher tous les articles
	public function index()
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['page']  = 'home';
			$data['title'] = 'Ma dashboard';
			$contents = $data['query'] = $this->get_all_content();

			$data['nb_comments'] = $this->get_comments();
/*			if ($nb_comments > 0):
				$data['nb_comments'] = $nb_comments;
			endif;*/

/*			$data['stats'] = array('articles'     => $contents->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou éditer un article
	public function edit($c_id = '')
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			// Chargement des rubriques (inputs)
			$rubrics = $data['rubrics'] = $this->get_all_rubrics();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $rubrics->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			// Chargement des users (select)
			$data['users'] = $this->model_user->get_users();

			// Mise en place du formulaire
			$this->form_validation->set_rules('c_title', 'Titre', 'trim|required|callback_check_content');
			$this->form_validation->set_rules('c_content', 'Contenu', 'trim|required');
			$this->form_validation->set_rules('c_image', 'Image d\'illustration', 'trim');
			$this->form_validation->set_rules('c_state', 'Etat', 'required');
			$this->form_validation->set_rules('rubric', 'Rubrique', 'required');
			$this->form_validation->set_rules('u_id', 'u_id', 'required');

			// Assignations du formulaire
			$c_title   = $this->input->post('c_title');
			$c_content = $this->input->post('c_content');
			$c_image   = $this->input->post('c_image');
			$c_state   = $this->input->post('c_state');
			$r_id 	   = $this->input->post('rubric');
			$u_id 	   = $this->input->post('u_id');

			$data['images'] = get_dir_file_info('./assets/img', $top_level_only = FALSE);

			// On vérifie si c'est pour ajouter ou modifier via l'URI
			if ($this->uri->total_segments() == 3):
				//$this->form_validation->set_rules('c_title', 'Titre', 'trim|required|callback_check_content');
				$data['page']  = 'add_content';
				$data['title'] = 'Ajouter un article';				

				// Réécriture du titre pour la future URL de l'article
				$c_url_rw = url_title(convert_accented_characters($c_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_content->create_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_state, $c_url_rw);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" ajouté.');
					redirect(URL_HOME_CONTENT);
				endif;

			else:
				$get_content = $this->model_content->get_content($c_id, '');
				// Vérification de l'id
				if ($get_content->num_rows() == 1):
					$this->form_validation->set_rules('c_udate', 'Date de mise à jour');

					$c_udate 		   = (isset($_POST['c_udate']))?true:false;
					$data['page']	   = 'edit_content';
					$row 			   = $get_content->row();
					$data['r_id']	   = $row->r_id;
					$data['u_id']	   = $row->u_id;
					$data['c_title']   = $row->c_title;
					$data['c_content'] = $row->c_content;
					$data['c_image']   = $row->c_image;
					$data['c_state']   = $row->c_state;
					$data['c_url_rw']  = $row->c_url_rw;
					$data['title']	   = 'Modifier l\'article ' . $data['c_title'];

					// Vérification pour l'url
					$this->form_validation->set_rules('c_url_rw', 'Url', 'trim|required|callback_check_url_rw');
					$c_url_rw = $this->input->post('c_url_rw');

					if ($this->form_validation->run() !== FALSE):
						$this->model_content->update_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_state, $c_url_rw, $c_udate, $c_id);
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
		$c_id = $this->uri->segment(4);

		if ($this->model_content->check_title($c_id, $c_title)->num_rows() == 1):
			$this->form_validation->set_message('check_content', 'Impossible de rajouter l\'article "' . $c_title . '" car ce dernier existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Vérifier si l'URL existe déjà
	public function check_url_rw($c_url_rw)
	{
		$c_id = $this->uri->segment(4);

		if ($this->model_content->check_url_rw($c_id, $c_url_rw)->num_rows() == 1):
			$this->form_validation->set_message('check_url_rw', 'Impossible d\'attribuer l\'url "' . $c_url_rw . '" car cette dernière existe déjà.');
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

	// Afficher les articles par auteur
	public function author($id = '')
	{
		if ($this->session->userdata('logged_in') and !empty($id)):

			$user = $this->model_user->get_user($id, '');
			
			if ($user->num_rows() == 1): 
				$data['login']   = $this->get_login();
				$data['level']   = $this->get_level();
				$data['id_user'] = $this->get_id_user();

				$data['nb_comments'] = $this->get_comments();

/*				$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
									   'rubriques'    => $this->get_all_rubrics()->num_rows(),
									   'utilisateurs' => $this->get_all_user()->num_rows()
									  );
*/
				$user = $user->row()->u_login;

				$data['page']  = 'author';
				$data['title'] = 'Tous les articles de ' . $user;

				$data['query'] = $this->model_content->get_content_by_user($id, '');

				$this->load->view(URL_LAYOUT, $data);

			else:
				$this->session->set_flashdata('alert', 'Cette auteur n\'existe pour ou n\'a jamais existé');
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
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['page']  = 'rubric';
			$data['title'] = 'Rubriques';

			$rubrics = $data['query'] = $this->get_all_rubrics();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $rubrics->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou modifier une rubrique
	public function edit_rubric($r_id = '')
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								   );*/

			// Mise en place du formulaire via form-validation
			$this->form_validation->set_rules('r_title', 'Titre', 'trim|required|callback_check_rubric_title');
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
					$data['r_url_rw'] 	   = $row->r_url_rw;
					$data['title'] 		   = 'Modifier la rubrique ' . $data['r_title'];

					// Vérification pour l'url
					$this->form_validation->set_rules('r_url_rw', 'Url', 'trim|required|callback_check_rubric_url_rw');
					$r_url_rw = $this->input->post('r_url_rw');

					if($this->form_validation->run() !== FALSE):
						$this->model_rubric->update_rubric($r_title, $r_description, $r_url_rw, $r_id);
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


	// Vérifier si l'article existe
	public function check_rubric_title($r_title)
	{
		$r_id = $this->uri->segment(4);

		if ($this->model_rubric->check_title($r_id, $r_title)->num_rows() == 1):
			$this->form_validation->set_message('check_rubric_title', 'Impossible de rajouter la rubrique "' . $r_title . '" car cette dernière existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Vérifier si l'URL existe déjà
	public function check_rubric_url_rw($r_url_rw)
	{
		$r_id = $this->uri->segment(4);

		if ($this->model_rubric->check_url_rw($r_id, $r_url_rw)->num_rows() == 1):
			$this->form_validation->set_message('check_rubric_url_rw', 'Impossible d\'attribuer l\'url "' . $r_url_rw . '" car cette dernière existe déjà.');
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
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['page']  = 'users';
			$data['title'] = 'Utilisateurs';

			$users = $data['query'] = $this->get_all_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $users->num_rows()
								  );
*/
			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou modifier un utilisateur
	public function edit_user($u_id = '')
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								   );
*/
			// Si l'user est bien un admin
			if ($data['level'] == 1):

				// Mise en place du formulaire via form-validation
				$this->form_validation->set_rules('u_level', 'Level', 'required');
				$this->form_validation->set_rules('u_login', 'Login', 'trim|required|callback_check_user');
				$this->form_validation->set_rules('u_biography', 'Login', 'trim');

				// Assignations du formulaire
				$u_login 	 = $this->input->post('u_login');
				$u_level 	 = $this->input->post('u_level');
				$u_biography = $this->input->post('u_biography');

				// On vérifie si c'est pour ajouter ou modifier via l'URI
				if ($this->uri->total_segments() == 3):
					$this->form_validation->set_rules('u_pass', 'Pass', 'trim|required');

					$u_pass  = $this->input->post('u_pass');
					$data['page']  = 'add_user';
					$data['title'] = 'Ajouter un utilisateur';

					if ($this->form_validation->run() !== FALSE):
						$this->model_user->create_user($u_login, $u_pass, $u_level, $u_biography);
						$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice "' . $u_login . '" ajouté(e)');
						redirect(base_url(URL_HOME_USERS));
					endif;

				else:
					$data['page']		 = 'edit_user';
					$row 		     	 = $this->model_user->get_user($u_id, '')->row();
					$data['u_login'] 	 = $row->u_login;
					$data['u_level'] 	 = $row->u_level;
					$data['u_biography'] = $row->u_biography;
					$data['title']   = 'Modifier l\'utilisateur ' . $data['u_login'];

					if ($this->form_validation->run() !== FALSE):
						$this->model_user->update_user($u_login, $u_level, $u_biography, $u_id);
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
		$u_id = $this->uri->segment(4);

		if ($this->model_user->check_user($u_id, $u_login)->num_rows() == 1):
			$this->form_validation->set_message('check_user', 'Impossible de rajouter ou de modifier l\'utilisateur "' . $u_login . '" car ce dernier existe déjà.');
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

	// Changer son mot de passe
	public function change_password()
	{
		if ($this->session->userdata('logged_in')):
			$data['login'] = $this->get_login();
			$data['level'] = $this->get_level();
			$u_id = $data['id_user'] = $this->get_id_user();

			$data['page']  = 'change_password';
			$data['title'] = 'Changer mon mot de passe';

			// Mise en place du formulaire via form-validation
			$this->form_validation->set_rules('u_old_pass', 'Ancien password', 'trim|required|callback_check_password');
			$this->form_validation->set_rules('u_pass', 'Nouveau password', 'trim|required');

			// Assignations du formulaire
			$u_old_pass = $this->input->post('u_old_pass');
			$u_new_pass = $this->input->post('u_pass');

			if ($this->form_validation->run() !== FALSE):
				$this->model_user->update_password_user($u_new_pass, $u_id);
				$this->session->set_flashdata('success', 'Votre mot de passe a bien été validé et marchera à la première reconnexion.');
				redirect(base_url(URL_HOME_USERS));
			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Vérifier l'ancien mot de passe
	public function check_password($u_old_pass)
	{
		$id_user = $this->get_id_user();

		if ($this->model_user->check_user_password($id_user, $u_old_pass)->num_rows() == 0):
			$this->form_validation->set_message('check_password', 'L\'ancien mot de passe n\'est pas bon.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Affiche les commentaires
	public function comment()
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			$data['page']  = 'comment';
			$data['title'] = 'Commentaires';

			$data['query'] = $this->model_comment->get_comments();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	function moderate_comment($com_id)
	{
		if ($this->session->userdata('logged_in')):

			// On vérifie si le commentaire existe
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->update_comment($com_id, $com_status = 1);
				$this->session->set_flashdata('success', 'Commentaire n°'. $com_id .' modéré.');
				redirect(URL_HOME_COM);

			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;

		else:
			$this->get_redirect();
		endif;
	}

	function desactivate_comment($com_id)
	{
		if ($this->session->userdata('logged_in')):

			// On vérifie si le commentaire existe
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->update_comment($com_id, $com_status = 2);
				$this->session->set_flashdata('success', 'Commentaire n°'. $com_id .' désactivé (n\'apparaitra plus sur le site).');
				redirect(URL_HOME_COM);

			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;

		else:
			$this->get_redirect();
		endif;
	}

	function delete_comment($com_id)
	{
		if ($this->session->userdata('logged_in')):
			
			// On vérifie si le commentaire existe
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->delete_comment($com_id);
				$this->session->set_flashdata('success', 'Commentaire n°'. $com_id .' supprimé.');
				redirect(URL_HOME_COM);
			
			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;		
		else:
			$this->get_redirect();
		endif;
	}

	// Affiche la galerie
	public function gallery()
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			$data['page']  = 'gallery';
			$data['title'] = 'Galerie';

			$data['query'] = get_dir_file_info('./assets/img', $top_level_only = FALSE);

			if(isset($_GET['delete']))
			{
				unlink('./assets/img/' . $_GET['delete']);
				$this->session->set_flashdata('success', 'Image ' . $_GET['delete'] . ' supprimée.');
				redirect(base_url('admin/dashboard/gallery'));
			}

			$this->upload();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}


	public function upload($error = array())
	{
		if ($this->session->userdata('logged_in')):

			$config['upload_path'] 	 = './assets/img/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']		 = '1024';
			$config['max_width']  	 = '1024';
			$config['max_height']  	 = '1024';

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload()):
				$error = array($this->upload->display_errors());
			else:
				$this->session->set_flashdata('success', 'Image importée.');
				redirect(base_url('admin/dashboard/gallery'));
			endif;

		else:
			$this->get_redirect();
		endif;
	}

	// Affiche les paramètres
	public function params()
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['nb_comments'] = $this->get_comments();

/*			$data['stats'] = array('articles'     => $this->get_all_content()->num_rows(),
								   'rubriques'    => $this->get_all_rubrics()->num_rows(),
								   'utilisateurs' => $this->get_all_user()->num_rows()
								  );*/

			// Si l'user est bien un admin
			if ($data['level'] == 1):

				$data['page']  = 'params';
				$data['title'] = 'Administration des paramètres';

				// Mise en place du formulaire via form-validation
				$this->form_validation->set_rules('p_title', 'Titre', 'trim|required');
				$this->form_validation->set_rules('p_m_description', 'Méta description', 'required');
				$this->form_validation->set_rules('p_about', 'A propos', 'required');

				// Assignations du formulaire
				$p_title 		 = $this->input->post('p_title');
				$p_m_description = $this->input->post('p_m_description');
				$p_about  		 = $this->input->post('p_about');

				// Récupération des données existantes
				$row = $this->model_params->get_params()->row();
				if(!empty($row)):	
					$data['p_title'] 		 = $row->p_title;
					$data['p_about']  		 = $row->p_about;
					$data['p_m_description'] = $row->p_m_description;
				endif;

				if ($this->form_validation->run() !== FALSE):
					if (empty($row)):
						$this->model_params->insert_params($p_title, $p_m_description, $p_about);
					else:
						$this->model_params->update_params($p_title, $p_m_description, $p_about);
					endif;
					$this->session->set_flashdata('success', 'Les paramètres ont bien été mis à jour.');
					redirect(base_url(URL_HOME_CONTENT));
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

	public function search()
	{
		if ($this->session->userdata('logged_in')):
			$data['login']   = $this->get_login();
			$data['level']   = $this->get_level();
			$data['id_user'] = $this->get_id_user();

			$data['page']  = 'search';
			$data['title'] = 'Mots recherchés';

			$data['query']			= $this->model_search->get_words();
			$data['distinct_words'] = $this->model_search->distinct_words();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;			
	}


	// Obtenir l'id de l'utilisateur
	private function get_id_user()
	{
		$data = $this->session->userdata('logged_in');

		return $data['id'];
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

	// Retourne tous les articles
	private function get_all_content()
	{
		$data = $this->model_content->get_contents('', '');

		return $data;
	}

	// Retourne toutes les rubriques
	private function get_all_rubrics()
	{
		$data = $this->model_rubric->get_rubrics();

		return $data;
	}

	// Retourne tous les utilisateurs
	private function get_all_user()
	{
		$data = $this->model_user->get_users();

		return $data;
	}

	// Retourne le nombre de commentaires non modérés
	private function get_comments()
	{
		$data = $this->model_comment->get_unmoderate_comments()->num_rows();

		return $data;
	}

}


/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */