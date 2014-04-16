<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_comment', 'model_params', 'model_rubric', 'model_search', 'model_user'));
		$this->load->library(array('form_validation', 'front'));
		$this->load->helper('functions');
		define('URL_LAYOUT', 'front/view_layout');
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	public function index()
	{
		$params = $this->front->about();

		$data['page']		= 'contact';
		$data['title']		= 'Contact';
		$data['meta_title'] = $data['title'] . ' - ' . $params->p_title;
		$data['p_title']	= $params->p_title;
		$data['meta_desc']  = $params->p_m_description;
		$data['about']		= $params->p_about;

		$data['breadcrumb'] = 'Contact';

		$data['all_content']	   = $this->front->get_all_content();
		$data['query_all_rubrics'] = $this->front->get_all_rubrics();
		$data['all_authors']	   = $this->front->get_all_authors();

		$this->load->view(URL_LAYOUT, $data);
	}

}


/* End of file contact.php */
/* Location: ./application/controllers/contact.php */