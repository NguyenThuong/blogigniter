<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		// Chargement des ressources pour ce controller
		$this->load->model(array('model_content', 'model_rubric', 'model_user', 'model_comment', 'model_params', 'model_search'));
		$this->load->library(array('pagination', 'form_validation', 'session'));
		$this->load->helper(array('functions', 'text', 'captcha'));
		define('URL_LAYOUT', 'front/view_layout');
		$this->output->enable_profiler(TRUE);
	}


	public function index($numero_page = 1)
	{
		$params = $this->about();

		$data['page']  = 'home';
		$data['title'] = $data['meta_title'] = $params->p_title;
		if ($numero_page > 1):
			$data['numero_page'] = $numero_page;
			$data['meta_title'] .= ' - page ' . $numero_page;
		endif;
		$data['p_title']   = $params->p_title;
		$data['meta_desc'] = $params->p_m_description;
		$data['about'] 	   = $params->p_about;

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();

		// Paramêtres pour la pagination
		$config = pagination_custom();

		# Chemin
		$config['base_url']    = base_url('page');
		# Lien de la 1ère page de la pagination
		$config['first_url']   = base_url();
		# Nombre de chiffres dans la pagination
		$config['total_rows']  = $data['all_content']->num_rows();
		# Nombre total de pages possibles
		$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
		# page/1 donc 2 en URI
		$config['uri_segment'] = 2;

		// Initialisation de la pagination
		$this->pagination->initialize($config);

		// Vérification du numéro de la page
		if ($numero_page > $config['num_links']):
			redirect(show_404());
		else:
			$data['query'] = $this->model_content->get_contents_listing('', $numero_page, $config['per_page']);
			// Génération de la pagination
			$data['pagination'] = $this->pagination->create_links();
			$data['meta_pagination'] = $this->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page']);
		endif;

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}


	public function view($slug_rubric = '', $slug_content = '', $numero_page = 1)
	{
		$params = $this->about();
		$data['p_title'] = $params->p_title;
		$data['about'] 	 = $params->p_about;

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();

		// Cas d'une rubrique
		if ($this->uri->total_segments() == 1 or $this->uri->total_segments() == 3):

			// Récupération de tous les articles (listing)
			$data['all_content'] = $this->get_all_content();

			// Paramêtres pour la pagination
			$config = pagination_custom();

			# Chemin
			$config['base_url']    = base_url($slug_rubric . '/page');
			# Lien de la 1ère page de la pagination
			$config['first_url']   = base_url($this->uri->segment(1));
			# Nombre de chiffres dans la pagination
			$config['total_rows']  = $this->model_content->get_contents_rubric_listing($slug_rubric, '', '')->num_rows();
			# Nombre total de pages possibles
			$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
			# "nom_rubrique/page/1" donc 3 en URI
			$config['uri_segment'] = 3;

			// Initialisation de la pagination
			$this->pagination->initialize($config);

			// Vérification du numéro de la page
			if($numero_page > $config['num_links']):
				redirect(show_404());
			else:
				// Récupération du contenu de la rubrique
				$data['query'] = $this->model_content->get_contents_rubric_listing($slug_rubric, $numero_page, $config['per_page']);
				// Si la reqûete abouti sans résultat, on redirige vers la page 404
				if ($data['query']->num_rows == 0):
					redirect(show_404());
				endif;
				// Génération de la pagination
				$data['pagination'] = $this->pagination->create_links();
			endif;

			// On récupère la valeur de r_url_rw pour faire une vérification ensuite
			$row = $data['query']->row(); 

			$data['page']  = 'rubric';
			// Encapsulation des données
			$data['title'] = $row->r_title;
			$data['meta_title'] = $row->r_title . ' - ' . $params->p_title;
			if ($numero_page > 1):
				$data['numero_page'] = $numero_page;
				$data['meta_title'] .= ' - page ' . $numero_page;
			endif;
			$data['meta_desc'] = $row->r_description;

			$data['meta_pagination'] = $this->get_pagination_seo($config['base_url'], $config['first_url'], $numero_page, $config['total_rows'], $config['per_page']);


		// Cas d'un article
		elseif ($this->uri->total_segments() == 2):
			// Récupération du contenu de l'article
			$query_article = $this->model_content->get_content_by_slug($slug_rubric, $slug_content);

				// Si la requête sort un résultat
				if ($query_article->num_rows() == 1):
					$data['page'] = 'content';
					// Encapsulation des données
					$row                = $query_article->row();
					$c_id  				= $row->c_id;
					$data['title']      = $data['c_title'] = $row->c_title;
					$data['c_content']  = $row->c_content;
					$data['c_image']  	= $row->c_image;
					$data['c_cdate']    = $row->c_cdate;
					$data['c_udate']    = $row->c_udate;
					$data['c_url_rw']   = $row->c_url_rw;
					$data['r_title']    = $row->r_title;
					$data['r_url_rw']   = $row->r_url_rw;
					$data['u_id']    	= $row->u_id;
					$data['u_login']    = $row->u_login;
					$data['u_biography']= $row->u_biography;

					$data['meta_title'] = $row->c_title . ' - ' . $params->p_title;
					$data['meta_desc']  = character_limiter(strip_tags($row->c_content, 160));

					// Tous les article du même auteur
					$data['query_same_user'] = $this->model_content->get_content_by_user($data['u_id'], 5);

					// Récupération du contenu des autres articles de la même rubrique
					$data['query_same_rubric'] = $this->model_content->get_contents_same_rubric($slug_rubric, $slug_content);

					// Récupération du contenu des autres articles (sidebar)
					$data['all_content'] = $this->model_content->get_contents_others($slug_content);

					// Commentaires
					$data['comments'] = $this->model_comment->get_comment($c_id);

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

					// Mise en place du formulaire
					$this->form_validation->set_rules('com_nickname', 'Nom', 'trim|required|min_length[2]');
					$this->form_validation->set_rules('com_content', 'Contenu', 'trim|required|min_length[2]');
					//$this->form_validation->set_rules('captcha', 'captcha', 'trim|required|callback_check_captcha[' . $word . ']');

					// Assignations du formulaire
					$com_nickname = $this->input->post('com_nickname');
					$com_content  = $this->input->post('com_content');
					$captcha  	  = $this->input->post('captcha');

					$this->form_validation->set_message('com_nickname', 'Le pseudo doit faire 2 caractères mininum');
					$this->form_validation->set_message('com_content', 'Le pseudo doit faire 2 caractères mininum');

					// Si formulaire bon
					if ($this->form_validation->run() !== FALSE):
						$this->model_comment->create_comment($c_id, $com_nickname, $com_content);
						$this->session->set_flashdata('success', 'Commentaire ajouté.');
						redirect(current_url());
					endif;

				// Sinon on redirige vers la page 404
				else:
					redirect(show_404());
				endif;

		else:
			redirect(show_404());
		endif;

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}

	// Vérifier si le captcha est bon
	public function check_captcha($captcha, $word)
	{
		if ($captcha === $word):
			return TRUE;
		else:
			$this->form_validation->set_message('check_captcha', 'Captcha invalide, envoi du commentaire impossible -- '. $captcha . ' ---- '. $word);
			return FALSE;
		endif;
	}

	public function auteur($u_login = '', $numero_page = 1)
	{
		$params = $this->about();
		$data['p_title'] = $params->p_title;
		$data['about'] 	 = $params->p_about;

		$data['title'] =  'A propos de ' . $u_login .' <br /><small>'  .$this->model_user->get_user('', $u_login)->row()->u_biography . '</small>';
		$data['meta_title'] = $data['meta_desc'] = 'Tous les articles de ' . $u_login;

		$data['page']    = 'author';
		$data['u_login'] = $u_login;

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();


		// Paramêtres pour la pagination
		$config = pagination_custom();
		# Chemin
		$config['base_url']    = base_url('auteur/' . $u_login .'/page');
		# Lien de la 1ère page de la pagination
		$config['first_url']   = base_url('auteur/' . $u_login);
		# Nombre de chiffres dans la pagination
		$config['total_rows']  = $this->model_content->get_contents_listing($u_login, '', '')->num_rows();
		# Nombre total de pages possibles
		$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
		# URI bug :(
		$config['uri_segment'] = 4;

		// Initialisation de la pagination
		$this->pagination->initialize($config);

		$numero_page = $this->uri->segment(4);
		// Vérification du numéro de la page
		if ($numero_page > $config['num_links']):
			redirect(show_404());
		else:
			$data['query'] = $this->model_content->get_contents_listing($u_login, $numero_page, $config['per_page']);
			// Génération de la pagination
			$data['pagination'] = $this->pagination->create_links();
		endif;

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}

	public function search($numero_page = 1)
	{
		$params = $this->about();
		$data['p_title'] = $params->p_title;
		$data['about'] 	 = $params->p_about;
		$data['meta_desc'] = '';

		$data['page'] = 'search';

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();

		// Si le champ name "q" est bien rempli
		if($this->input->get('q')):
			
			// Si la longeur champ name "q" est supérieure à 2 et qu'il ne comporte pas d'espace
			if(strlen(trim($this->input->get('q'))) > 2):
				// On récupère sa valeur
				$data['research'] = $research = $this->input->get('q');

				// Paramêtres pour la pagination
				$config = pagination_custom();
				# Chemin
				$config['base_url']    = base_url('s?q=' . $research);
				# Lien de la 1ère page de la pagination
				$config['first_url']   = base_url('s?q=' . $research);
				# Nombre de chiffres dans la pagination
				$config['total_rows']  = $this->model_search->get_research($research, '', '')->num_rows();

				# Nombre total de pages possibles
				$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
				# URI bug :(
				$config['uri_segment'] = 3;
				$config['page_query_string'] = TRUE;

				// Initialisation de la pagination
				$this->pagination->initialize($config);

				$data['numero_page'] = $numero_page = $this->pagination->number_page();

				// Vérification du numéro de la page
				if ($numero_page > $config['num_links']):
					redirect(show_404());
				else:
					$data['query'] = $this->model_search->get_research($research, $numero_page, $config['per_page']);
					// Génération de la pagination
					$data['pagination'] = $this->pagination->create_links();
				endif;

				// Import dans la base
				$this->model_search->insert_search($research, $config['total_rows']);

				$data['title'] = $data['meta_title'] = 'Recherche ' . $research . ' (' .$config['total_rows'] . ')' ;

			else:
				$data['title'] = 'Erreur dans votre recherche';
				$data['error'] = $data['meta_title'] = 'Votre requête ne peut aboutir car le mot rentré doit comporté au minimum 2 mots.';
			endif;

		endif;

		// Importation des données dans la vue
		if(!isset($data)):
			$data= '';
		endif;
		$this->load->view('front/view_layout', $data);

	}

	public function erreur404()
	{

		$params = $this->about();
		$data['about'] = $params->p_about;

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();

		$data['page']  = '404';
		$data['title'] = $data['meta_title'] = $data['meta_desc'] = 'Erreur 404 sur la page demandée';
		
		// Instancie une vraie erreur 404
		http_response_code(404);

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}


	private function about()
	{
		$data = $this->model_params->get_params()->row();

		return $data;
	}


	// Retourne tous les articles (sidebar)
	private function get_all_content()
	{
		$data = $this->model_content->get_contents(true, 1);

		return $data;
	}

	// Retourne toutes les rubriques (sidebar)
	private function get_all_rubrics()
	{
		$data = $this->model_rubric->get_rubrics_front();

		return $data;
	}

	// Retourne tous les auteurs (sidebar)
	private function get_all_authors()
	{
		$data = $this->model_user->get_users();

		return $data;
	}

	// SEO pagination
	private function get_pagination_seo($url, $first_url, $numero_page, $total, $per_page)
	{
		// cas 1ère page
		if ($numero_page == 1):
			if ($numero_page > $per_page):
				$data = '<link rel="next" href="' . $url . '/' . ($numero_page+1) . '">' . "\n";
			else:
				$data = '';
			endif;

		// cas dernière page
		elseif($numero_page == ceil($total/$per_page)):
			if ($numero_page == 2):
				$data = '<link rel="prev" href="' . $first_url . '">'."\n";
			else:
				$data = '<link rel="prev" href="' . $url . '/' . ($numero_page-1) . '">' . "\n";
			endif;
		else:
			$data = '<link rel="prev" href="' . $url . '/' . ($numero_page-1) . '">' . "\n";
			$data.= '<link rel="next" href="' . $url . '/' . ($numero_page+1) . '">' . "\r";
		endif;

		return $data;
	}

}


/* End of file blog.php */
/* Location: ./application/controllers/admin/blog.php */