<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		// Chargement des ressources pour ce controller
		$this->load->model(array('model_content', 'model_rubric', 'model_user'));
		$this->load->library('pagination');
		$this->load->helper(array('functions', 'text'));
		define('URL_LAYOUT', 'front/view_layout');
		define('URL_404'   , 'erreur404');
		$this->output->enable_profiler(TRUE);
	}

	// Retourne tous les articles (sidebar)
	private function get_all_content()
	{
		$data = $this->model_content->get_contents();

		return $data;
	}

	// Retourne toutes les rubriques (sidebar)
	private function get_all_rubrics()
	{
		$data = $this->model_rubric->get_rubrics_front();

		return $data;
	}


	public function index($numero_page = 1)
	{
		$data['page']       = 'home';
		$data['title']      = 'Mon blog Codeigniter';
		$data['meta_title'] = 'Mon Blog';
		if ($numero_page > 1):
			$data['numero_page'] = $numero_page;
			$data['meta_title'] .= ' - page ' . $numero_page;
		endif;
		$data['meta_desc']  = "Mon super blog propulsé par Codeigniter 2";

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

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
			redirect(base_url(URL_404), 404);
		else:
			$data['query'] = $this->model_content->get_contents_listing($numero_page, $config['per_page'] );
			// Génération de la pagination
			$data['pagination'] = $this->pagination->create_links();
		endif;

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}


	public function view($slug_rubric = '', $slug_content = '', $numero_page = 1)
	{
		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

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
				redirect(base_url(URL_404), 404);
			else:
				// Récupération du contenu de la rubrique
				$data['query'] = $this->model_content->get_contents_rubric_listing($slug_rubric, $numero_page, $config['per_page'] );
				// Si la reqûete abouti sans résultat, on redirige vers la page 404
				if ($data['query']->num_rows == 0):
					redirect(base_url(URL_404), 404);
				endif;
				// Génération de la pagination
				$data['pagination'] = $this->pagination->create_links();
			endif;

			// On récupère la valeur de r_url_rw pour faire une vérification ensuite
			$row = $data['query']->row(); 

			$data['page']  = 'rubric';
			// Encapsulation des données
			$data['title'] = $data['meta_title'] = $row->r_title;
			if ($numero_page > 1):
				$data['numero_page'] = $numero_page;
				$data['meta_title'] .= ' - page ' . $numero_page;
			endif;
			$data['meta_desc'] = $row->r_description;


		// Cas d'un article
		elseif ($this->uri->total_segments() == 2):
			// Récupération du contenu de l'article
			$data['query_article'] = $this->model_content->get_content_by_slug($slug_rubric, $slug_content);

				// Si la requête sort un résultat
				if ($data['query_article']->num_rows() == 1):
					$data['page'] = 'content';
					// Encapsulation des données
					$row                = $data['query_article']->row();
					$data['title']      = $data['c_title'] = $row->c_title;
					$data['c_content']  = $row->c_content;
					$data['c_cdate']    = $row->c_cdate;
					$data['c_url_rw']   = $row->c_url_rw;
					$data['r_title']    = $row->r_title;
					$data['r_url_rw']   = $row->r_url_rw;

					$data['meta_title'] = $row->c_title;
					$data['meta_desc']  = character_limiter(strip_tags($row->c_content, 160));

					// Récupération du contenu des autres articles de la même rubrique
					$data['query_same_rubric'] = $this->model_content->get_contents_same_rubric($slug_rubric, $slug_content);

					// Récupération du contenu des autres articles (sidebar)
					$data['all_content'] = $this->model_content->get_contents_others($slug_content);

				// Sinon on redirige vers la page 404
				else:
					redirect(base_url(URL_404), 404);

				endif;

		else:
			redirect(base_url(URL_404), 404);

		endif;

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}


	public function erreur404()
	{

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		$data['page']  = '404';
		$data['title'] = $data['meta_title'] = $data['meta_desc'] = 'Erreur 404 sur la page demandée';
		
		// Instancie une vraie erreur 404
		http_response_code(404);

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);
	}

}


/* End of file blog.php */
/* Location: ./application/controllers/admin/blog.php */