<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content', 'model_rubric', 'model_user'));
		$this->load->library(array('admin/functions', 'session'));
		$this->load->helper(array('form', 'functions', 'text'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/content');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display all contents
	public function index()
	{
		if ($this->functions->get_loged()):

			$data['user_data'] = $this->functions->get_user_data();

			$data['page']  = 'home';
			$data['title'] = 'Ma dashboard';		
			$data['query'] = $this->functions->get_all_content();

			$data['nb_comments'] = $this->functions->get_comments();

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Add or edit a content
	public function edit($c_id = '')
	{
		if ($this->functions->get_loged()):

			$this->load->library('form_validation');
			$this->load->helper('file');

			$data['user_data']   = $this->functions->get_user_data();
			$data['rubrics'] 	 = $this->functions->get_all_rubrics();
			$data['nb_comments'] = $this->functions->get_comments();
			$data['users'] 		 = $this->model_user->get_users();
			$data['images'] 	 = get_dir_file_info('./assets/img/thumb', $top_level_only = FALSE);

			$this->form_validation->set_rules('c_title', 'Titre', 'trim|required|callback_check_content');
			$this->form_validation->set_rules('c_content', 'Contenu', 'trim|required');
			$this->form_validation->set_rules('c_image', 'Image d\'illustration', 'trim');
			$this->form_validation->set_rules('c_state', 'Etat', 'required');
			$this->form_validation->set_rules('rubric', 'Rubrique', 'required');
			$this->form_validation->set_rules('c_tags', 'Rubrique', 'required');
			$this->form_validation->set_rules('u_id', 'u_id', 'required');

			$c_title   = $this->input->post('c_title');
			$c_content = $this->input->post('c_content');
			$c_image   = $this->input->post('c_image');
			$c_state   = $this->input->post('c_state');
			$r_id 	   = $this->input->post('rubric');
			$c_tags    = $this->input->post('c_tags');
			$u_id 	   = $this->input->post('u_id');

			// Add a content
			if ($this->uri->total_segments() == 3):
				$data['page']  = 'add_content';
				$data['title'] = 'Ajouter un article';				

				$c_url_rw = url_title(convert_accented_characters($c_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_content->create_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $c_state, $c_url_rw);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" ajouté.');
					redirect(URL_HOME_CONTENT);
				endif;

			else:
				$get_content = $this->model_content->get_content($c_id, '');

				// Content exist
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
					$data['c_tags']    = $row->c_tags;
					$data['c_state']   = $row->c_state;
					$data['c_url_rw']  = $row->c_url_rw;
					$data['title']	   = 'Modifier l\'article ' . $data['c_title'];

					$this->form_validation->set_rules('c_url_rw', 'Url', 'trim|required|callback_check_url_rw');
					$c_url_rw = $this->input->post('c_url_rw');

					if ($this->form_validation->run() !== FALSE):
						$this->model_content->update_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $c_state, $c_url_rw, $c_udate, $c_id);
						$this->session->set_flashdata('success', 'Article "' . $c_title . '" modifié.');
						redirect(URL_HOME_CONTENT);
					endif;

				// Content unknown
				else:
					$this->session->set_flashdata('alert', 'Cette article n\'existe pas ou n\'a jamais existé');
					redirect(URL_HOME_CONTENT);
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Check if a content already exists
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

	// Check if the URL content already exists
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

	// Delete a content
	public function delete($id = '')
	{
		if ($this->functions->get_loged()):

			// Content exists
			if ($this->model_content->get_content($id)->num_rows() == 1):
				$this->model_content->delete_content($id);
				$this->session->set_flashdata('success', 'L\'article a bien été supprimé');
				redirect(base_url('admin'));

			// Content unknown 
			else:
				$this->session->set_flashdata('alert', 'Cette article n\'existe pour ou n\'a jamais existé');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

}


/* End of file content.php */
/* Location: ./application/controllers/admin/content.php */