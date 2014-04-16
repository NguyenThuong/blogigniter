<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content', 'model_params', 'model_rubric', 'model_search', 'model_user'));
		$this->load->library(array('pagination', 'form_validation', 'front', 'session'));
		$this->load->helper(array('functions', 'text', 'captcha'));
		define('URL_LAYOUT', 'front/view_layout');
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	public function index($numero_page = 1)
	{
		$params = $this->front->about();
		$data['page'] = 'home';

		if (!empty($params)):
			$data['title']	   = $data['meta_title'] = $params->p_title;
			$data['p_title']   = $params->p_title;
			$data['meta_desc'] = $params->p_m_description;
			$data['about']	   = $params->p_about;
		else:
			$data['title'] 	= $data['meta_title'] = $data['p_title'] = $data['meta_desc'] = $data['about'] = '';
		endif;

		$data['breadcrumb'] = 'Home';
			
		if ($numero_page > 1):
			$data['numero_page'] = $numero_page;
			$data['meta_title'] .= ' - page ' . $numero_page;
			$data['breadcrumb'] = '<a href="' . base_url() . '">' . $data['breadcrumb'] . '</a> - page ' . $numero_page;
		endif;

		$data['all_content']	   = $this->front->get_all_content();
		$data['query_all_rubrics'] = $this->front->get_all_rubrics();
		$data['all_authors']       = $this->front->get_all_authors();

		$config = pagination_custom();

		$config['base_url']    = base_url('page');
		$config['first_url']   = base_url();
		$config['total_rows']  = $data['all_content']->num_rows();
		$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
		$config['uri_segment'] = 2;

		$this->pagination->initialize($config);

		// Check number page for the pagination
		if ($numero_page > $config['num_links']):
			redirect(show_404());
		else:
			$data['query'] = $this->model_content->get_contents_listing('', $numero_page, $config['per_page']);
			$data['pagination'] = $this->pagination->create_links();
			$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page'], $type='POST');
		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	public function view($slug_rubric = '', $slug_content = '', $numero_page = 1)
	{
		$params = $this->front->about();

		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about'] 	 = $params->p_about;
			$data['twitter'] = $params->p_twitter;
		else:
			$data['p_title'] = $data['about'] = $data['twitter'] = '';
		endif;

		$data['query_all_rubrics'] = $this->front->get_all_rubrics();
		$data['all_authors']	   = $this->front->get_all_authors();

		// Rubric case
		if ( ($this->uri->total_segments() == 1) or ($this->uri->total_segments() == 3) ):

			$data['all_content'] = $this->front->get_all_content();

			$config = pagination_custom();

			$config['base_url']    = base_url($slug_rubric . '/page');
			$config['first_url']   = base_url($this->uri->segment(1));
			$config['total_rows']  = $this->model_content->get_contents_rubric_listing($slug_rubric, '', '')->num_rows();
			$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
			$config['uri_segment'] = 3;

			$this->pagination->initialize($config);

			if ($numero_page > $config['num_links']):
				redirect(show_404());
			else:
				$data['query'] = $this->model_content->get_contents_rubric_listing($slug_rubric, $numero_page, $config['per_page']);
				if ($data['query']->num_rows == 0):
					redirect(show_404());
				endif;
				$data['pagination'] = $this->pagination->create_links();
			endif;

			$row = $data['query']->row(); 

			$data['page']  = 'rubric';
			$data['title'] = $row->r_title;

			if (!empty($params)):
				$data['meta_title'] = $row->r_title . ' - ' . $params->p_title;
			else:
				$data['meta_title'] = $row->r_title;
			endif;

			if ($numero_page == 1):
				$data['breadcrumb'] = $data['title'];
			elseif ($numero_page > 1):
				$data['numero_page'] = $numero_page;
				$data['meta_title'] .= ' - page ' . $numero_page;
				$data['breadcrumb']  = '<a href="' . base_url($slug_rubric) . '">'.$data['title'].'</a> - page ' . $numero_page;
			endif;
			$data['meta_desc'] = $row->r_description;

			$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page'], $type='POST');

		// Article case
		elseif ($this->uri->total_segments() == 2):
			$query_article = $this->model_content->get_content_by_slug($slug_rubric, $slug_content);

			if ($query_article->num_rows() == 1):
				$data['page'] = 'content';
				$row 				= $query_article->row();
				$c_id				= $row->c_id;
				$data['title']		= $data['c_title'] = $row->c_title;
				$data['c_content']	= $row->c_content;
				$data['c_image']	= $row->c_image;
				$data['c_cdate']	= $row->c_cdate;
				$data['c_udate']	= $row->c_udate;
				$data['c_url_rw']	= $row->c_url_rw;
				$data['r_title']	= $row->r_title;
				$data['r_url_rw']	= $row->r_url_rw;
				$data['u_id']		= $row->u_id;
				$data['u_login']	= $row->u_login;
				$data['u_biography']= $row->u_biography;

				if (!empty($params)):
					$data['meta_title'] = $row->c_title . ' - ' . $params->p_title;
				else:
					$data['meta_title'] = $row->c_title;
				endif;

				$data['meta_desc']  = character_limiter(strip_tags($row->c_content, 160));

				$data['breadcrumb'] = $row->c_title;

				if (isset($row->c_tags)):
					$data['tags'] = explode(';', $row->c_tags);
				endif;

				$data['query_same_user']   = $this->model_content->get_content_by_user($data['u_id'], 5);
				$data['query_same_rubric'] = $this->model_content->get_contents_same_rubric($slug_rubric, $slug_content);
				$data['all_content'] 	   = $this->model_content->get_contents_others($slug_content);
				$data['comments']    	   = $this->model_comment->get_comment($c_id);

				// Génération du captcha
				$word = substr(sha1(rand()),-5);
				//$random = 'toto';
				$the_captcha = array(
					'word' 		 => $word,
					'img_path' 	 => 'captcha/',
					'img_url' 	 => site_url().'captcha/',
					'img_width'  => '150',
					'img_height' => 30,
					'expiration' => 7200
					);
				$data['captcha'] 	   = create_captcha($the_captcha);
				$data['captcha_image'] = $data['captcha']['image'];

				$this->form_validation->set_rules('com_nickname', 'Nom', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('com_content', 'Contenu', 'trim|required|min_length[2]');

				$com_nickname = $this->input->post('com_nickname');
				$com_content  = $this->input->post('com_content');
				$captcha  	  = $this->input->post('captcha');

				$this->form_validation->set_message('com_nickname', 'Le pseudo doit faire 2 caractères mininum');
				$this->form_validation->set_message('com_content', 'Le pseudo doit faire 2 caractères mininum');

				if ($this->form_validation->run() !== FALSE):
					$this->model_comment->create_comment($c_id, $com_nickname, $com_content);
					$this->session->set_flashdata('success', 'Commentaire ajouté.');
					redirect(current_url());
				endif;

			else:
				redirect(show_404());
			endif;

		else:
			redirect(show_404());
		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	// Vérifier si le captcha est bon
	public function check_captcha($captcha, $word)
	{
		if ($captcha === $word):
			return TRUE;
		else:
			$this->form_validation->set_message('check_captcha', 'Captcha invalide, envoi du commentaire impossible -- ' . $captcha . ' ---- ' . $word);
			return FALSE;
		endif;
	}

	public function auteur($u_login = '', $numero_page = 1)
	{
		$get_user = $this->model_user->get_user('', $u_login);

		if ($get_user->num_rows() == 1):
			$params = $this->front->about();

			if (!empty($params)):
				$data['p_title'] = $params->p_title;
				$data['about'] 	 = $params->p_about;
			else:
				$data['p_title'] = $data['about'] = '';
			endif;

			$data['title'] =  'A propos de ' . $u_login . ' <br /><small>'  . $get_user->row()->u_biography . '</small>';
			$data['meta_title'] = $data['meta_desc'] = 'Tous les articles de ' . $u_login;
			$data['page']		= 'author';
			$data['u_login']	= $u_login;

			$data['all_content'] 	   = $this->front->get_all_content();
			$data['query_all_rubrics'] = $this->front->get_all_rubrics();
			$data['all_authors'] 	   = $this->front->get_all_authors();

			$config = pagination_custom();

			$config['base_url']    = base_url('auteur/' . $u_login .'/page');
			$config['first_url']   = base_url('auteur/' . $u_login);
			$config['total_rows']  = $this->model_content->get_contents_listing($u_login, '', '')->num_rows();
			$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
			$config['uri_segment'] = 4;

			$this->pagination->initialize($config);

			$numero_page = $this->uri->segment(4);
			if ($numero_page > $config['num_links']):
				redirect(show_404());
			else:
				$data['query'] = $this->model_content->get_contents_listing($u_login, $numero_page, $config['per_page']);
				// Génération de la pagination
				$data['pagination'] = $this->pagination->create_links();
				$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page'], $type='POST');

			endif;

			if ($numero_page == 0):
				$data['breadcrumb'] = $u_login;
			else:
				$data['breadcrumb'] = '<a href="'. base_url('auteur/' . $u_login) .'">' . $u_login . '</a> - page ' . $numero_page;
			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			redirect(show_404());
		endif;
	}

	public function tags($numero_page = 1)
	{
		$params = $this->front->about();

		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about']	 = $params->p_about;
		else:
			$data['p_title'] = $data['about'] = '';
		endif;

		$data['page']		= 'rubric';

		$data['query_all_rubrics'] = $this->front->get_all_rubrics();
		$data['all_content']	   = $this->front->get_all_content();
		$data['all_authors']	   = $this->front->get_all_authors();

		if ($this->input->get('q')):

			$data['tag'] = $this->input->get('q');
			
			$data['meta_desc']  = 'Tag ' . $data['tag'];
			$data['title'] 		= $data['meta_title'] = 'Tag ' . $data['tag'];
			$data['breadcrumb'] = 'Tag ' . $data['tag'];

			$query = $this->model_content->get_content_by_tag_name($data['tag'], '', '');

			if ($query->num_rows() > 0):

				$config = pagination_custom();
				$config['base_url']    = base_url('t?q=' . $data['tag']);
				$config['first_url']   = base_url('t?q=' . $data['tag']);
				$config['total_rows']  = $query->num_rows();
				$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
				$config['uri_segment'] = 3;
				$config['page_query_string'] = TRUE;

				$this->pagination->initialize($config);

				$data['numero_page'] = $numero_page = $this->pagination->number_page();

				$data['breadcrumb'] = 'Tag : ';

				if ($numero_page == 0):
					$data['breadcrumb'] .= $data['tag'];
					$data['meta_desc'] = 'Tags' . $data['tag'];
				else:
					$data['breadcrumb'] .= '<a href="' . $config['base_url'] . '">' . $data['tag'] . '</a> - page ' . $numero_page;
					$data['meta_desc'] = 'Tag ' . $data['tag'] . ' - page ' . $numero_page;
				endif;

				if ($numero_page > $config['num_links']):
					redirect(show_404());
				else:
					$data['query'] = $this->model_content->get_content_by_tag_name($data['tag'], $numero_page, $config['per_page']);
					// Génération de la pagination
					$data['pagination'] 	 = $this->pagination->create_links();
					$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page'], $type='get');
				endif;

			else:
				$data['title'] = 'Erreur de tag';
				$data['breadcrumb'] = 'tag inéxistant';
				$data['error'] = $data['meta_title'] = 'Aucun article correspondant à ce tag.';
			endif;

		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	public function search($numero_page = 1)
	{
		$params = $this->front->about();

		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about']	 = $params->p_about;
		else:
			$data['p_title'] = $data['about'] = '';
		endif;

		$data['meta_desc'] = 'Page de recherche';

		$data['page']		= 'search';
		$data['breadcrumb'] = '';

		$data['query_all_rubrics'] = $this->front->get_all_rubrics();
		$data['all_content']	   = $this->front->get_all_content();
		$data['all_authors']	   = $this->front->get_all_authors();

		$input = $this->input->get('q');

		if (!empty($input)):

			if (strlen(trim($input)) > 2):
				$data['research'] = $research = $input;

				$explode = explode(' ', $research);
				if (count($explode) > 1):
					foreach ($explode as $words):
						if (strlen($words) > 2):
							$data['words'][] = $words;
						endif;
					endforeach;
				endif;

				$config = pagination_custom();
				$config['base_url']    = base_url('s?q=' . $research);
				$config['first_url']   = base_url('s?q=' . $research);
				$config['total_rows']  = $this->model_search->get_research($research, '', '')->num_rows();
				$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
				$config['uri_segment'] = 3;
				$config['page_query_string'] = TRUE;

				$this->pagination->initialize($config);

				$data['numero_page'] = $numero_page = $this->pagination->number_page();

				$data['breadcrumb'] = 'Recherche : ';

				if ($numero_page == 0):
					$data['breadcrumb'] .= $data['research'];
					$data['meta_desc'] = 'Résultats pour ' . $data['research'];
				else:
					$data['breadcrumb'] .= '<a href="' . $config['base_url'] . '">' . $data['research'] . '</a> - page ' . $numero_page;
					$data['meta_desc'] = 'Résultats pour ' . $data['research'] . ' - page ' . $numero_page;
				endif;

				if ($numero_page > $config['num_links']):
					redirect(show_404());
				else:
					$data['query'] = $this->model_search->get_research($research, $numero_page, $config['per_page']);
					$data['pagination'] = $this->pagination->create_links();
					$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page'], $type='get');
				endif;

				$this->model_search->insert_search($research, $config['total_rows']);

				$data['title'] = $data['meta_title'] = 'Recherche ' . $research . ' (' . $config['total_rows'] . ')' ;

			else:
				$data['title'] = 'Erreur dans votre recherche';
				$data['breadcrumb'] = 'Recherche : ' . strtolower($data['title']);
				$data['research'] = '';
				$data['error'] = $data['meta_title'] = 'Votre requête ne peut aboutir car le mot rentré doit comporté au minimum 2 mots.';
			endif;

		else:
			$data['research'] 	= '';
			$data['title'] 		= 'Oups';
			$data['breadcrumb'] = 'Recherche : ' . strtolower($data['title']);
		endif;

		$this->load->view('front/view_layout', $data);
	}

}


/* End of file blog.php */
/* Location: ./application/controllers/blog.php */