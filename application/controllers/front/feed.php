<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		// Chargement des ressources pour ce controller
		$this->load->helper('text');
		$this->load->model('model_feed');
		$this->load->helper('xml');
	}

	public function index()
	{
		$data['site_name']		  = 'Mon blog';
		$data['site_link']		  = base_url();
		$data['site_description'] = 'Les flux RSS de mes articles';
		$data['encoding']		  = 'utf-8';
		$data['feed_url']		  = base_url() . '/feed';
		$data['page_language']    = 'fr-fr';
		$data['posts']			  = $this->model_feed->getRecentPosts();
		header("Content-Type: application/rss+xml");

		$this->load->view('front/view_feed', $data);
	}
}


/* End of file feed.php */
/* Location: ./application/controllers/front/feed.php */