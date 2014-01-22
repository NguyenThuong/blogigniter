<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('admin/model_admin');
		$this->load->library(array('form_validation', 'session'));
		$this->load->helper(array('functions', 'text', 'url'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/dashboard');
		define('URL_HOME_RUBRIC' , 'admin/dashboard/rubric');
		session_start();
	}

	// Obtenir le login
	private function get_login()
	{
		$data = $this->session->userdata('logged_in');

		return $data['login'];
	}

	// Obtenir la redirection
	private function get_redirect()
	{
		$data = $this->session->set_flashdata('alert', 'Cette page est protégée par un mot de passe.').redirect(base_url('admin'));

		return $data;
	}

	// Afficher tous les articles
	function index()
	{
		if ($this->session->userdata('logged_in')):
			// Pour afficher le login
			$data['login'] = $this->get_login();

			$data['page']  = 'home';
			$data['title'] = 'Ma dashboard';
			$data['query'] = $this->model_admin->read_content();

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou éditer un article
	function edit($c_id = '')
	{
		if ($this->session->userdata('logged_in')):

			// Pour afficher le login
			$data['login'] = $this->get_login();

			// Chargement des rubriques
			$data['rubrics'] = $this->model_admin->read_rubric();
			
			// Mise en place du formulaire
			$this->form_validation->set_rules('c_title', 'Titre', 'trim|required');
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
					$this->model_admin->create_content($r_id, $c_title, $c_content, $c_url_rw);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" ajouté.');
					redirect(URL_HOME_CONTENT);
				endif;

			else:
				$data['page']  	   = 'edit_content';
				$row 			   = $this->model_admin->get_content($c_id)->row();
				$data['r_id']	   = $row->r_id;
				$data['c_title']   = $row->c_title;
				$data['c_content'] = $row->c_content;
				$data['title']	   = 'Modifer la rubrique ' . $data['c_title'];

				if ($this->form_validation->run() !== FALSE):
					$this->model_admin->update_content($r_id, $c_title, $c_content, $c_id);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" modifié.');
					redirect(URL_HOME_CONTENT);
				endif;

			endif;
			
			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Supprimer un article
	function delete($id = '')
	{
		if ($this->session->userdata('logged_in')):

			// Si l'utilisateur existe, on peut le supprimer
			if ($this->model_admin->get_content($id)->num_rows() == 1):
				$this->model_admin->delete_content($id);
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
	function rubric()
	{
		if ($this->session->userdata('logged_in')):

			// Pour afficher le login
			$data['login'] = $this->get_login();

			$data['page']  = 'rubric';
			$data['title'] = 'Rubriques';

			$data['query'] = $this->model_admin->read_rubric();

			$this->load->view(URL_LAYOUT, $data);


		else:
			$this->get_redirect();
		endif;
	}

	// Ajouter ou modifier une rubrique
	function edit_rubric($r_id = '')
	{
		if ($this->session->userdata('logged_in')):
			
			// Pour afficher le login
			$data['login'] = $this->get_login();
			
			// Mise en place du formulaire via form-validation
			$this->form_validation->set_rules('r_title', 'Titre', 'trim|required');
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
					$this->model_admin->create_rubric($r_title, $r_description, $r_url_rw);
					$this->session->set_flashdata('success', 'Rubrique "' . $r_title . '" ajoutée');
 					redirect(base_url(URL_HOME_RUBRIC));
				endif;

			else:
				$data['page']  		   = 'edit_rubric';
				$row 		   		   = $this->model_admin->get_rubric($r_id)->row();
				$data['r_title']	   = $row->r_title;
				$data['r_description'] = $row->r_description;
				$data['title'] 		   = 'Mofidifer la rubrique ' . $data['r_title'];

				if($this->form_validation->run() !== FALSE):
					$this->model_admin->update_rubric($r_title, $r_description, $r_id);
					$this->session->set_flashdata('success', 'Catégorie "' . $r_title . '" modifiée.');
					redirect(base_url(URL_HOME_RUBRIC));
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			$this->get_redirect();
		endif;
	}

	// Supprimer une catégorie
	function delete_rubric($r_id)
	{
		if ($this->session->userdata('logged_in')):

			// On vérifie si la rubrique existe toujours
			if ($this->model_admin->get_rubric($r_id)->num_rows() == 1):

				// On vérifie si il y a des articles rattachés à cette rubrique
				if ($this->model_admin->get_content_by_rubric($r_id)->num_rows() == 0):
					$this->model_admin->delete_rubric($r_id);
					$this->session->set_flashdata('success', 'Rubrique supprimée.');
				else:
					$this->session->set_flashdata('alert', 'Impossible de supprimer cette rubrique car il y a un ou plusieurs article(s) rattaché(s).');
				endif;

			else:
				$this->session->set_flashdata('alert', 'Cette rubrique n\'existe pas ou n\'a jamais existé.');
			endif;

			redirect(base_url(URL_HOME_RUBRIC));

		else:
			$this->get_redirect();
		endif;
	}

}


/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */