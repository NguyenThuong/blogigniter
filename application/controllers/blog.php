<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        // Chargement des ressources pour ce controller
        $this->load->database();
        $this->load->model('model_blog');
        $this->load->helper(array('datefr', 'text', 'url'));
    }


    public function index()
    {
        $data['title']               = 'Titre de mon blog';
        $data['meta_title']          = "Titre de mon blog dans le title";
        $data['meta_desc']           = "La description de mon blog pour le SEO";
        $data['query_all_rubriques'] = $this->model_blog->get_all_rubriques();

        // Retourne tous les articles
        $data['query'] = $data['all_content'] = $this->model_blog->get_all_content();

        // Chargement des données dans la vue
        $this->load->view('front/view_layout', $data);
    }


    public function view($slug_rubrique = '', $slug_content = '')
    {
        $data['query_all_rubriques'] = $this->model_blog->get_all_rubriques();

        // Récupération du contenu de la rubrique
        $data['query'] = $this->model_blog->get_rubrique($slug_rubrique);

        // Si le résulat n'est pas bon, on redirige vers la page d'accueil du blog
        if (empty($data['query'])):
            redirect('/');    # ou vous pouvez rediriger vers la page 404...
        endif;

        // Récupération du contenu de l'article
        $data['query_article'] = $this->model_blog->get_content($slug_content);

        // On récupère la valeur de r_url_rw pour faire une vérification ensuite
        $row = $data['query']->row();

        if ( ($this->uri->segment(1) == $row->r_url_rw) and ($this->uri->segment(2) ) ):
            // Si le résulat n'est pas bon, on redirige vers la rubrique
            if (empty($data['query_article'])):
                redirect('/' . $slug_rubrique);    # ou vous pouvez rediriger vers la page 404...
            else:
                $data['page'] = 'article';
                // Encapsulation des données
                $row                = $data['query_article']->row();
                $data['c_title']    = $row->c_title;
                $data['c_content']  = $row->c_content;
                $data['c_cdate']    = $row->c_cdate;
                $data['c_image']    = $row->c_image;
                $data['c_url_rw']   = $row->c_url_rw;
                $data['r_title']    = $row->r_title;
                $data['r_url_rw']   = $row->r_url_rw;

                $data['meta_title'] = $row->c_title;
                $data['meta_desc']  = character_limiter($row->c_content, 140);

                // Récupération du contenu des autres articles de la même rubrique
                $data['query_same_rubrique'] = $this->model_blog->get_same_rubrique($slug_rubrique, $slug_content);

                // Récupération du contenu des autres articles
                $data['all_content'] = $this->model_blog->get_others_content($slug_content);

                // Chargement des données dans la vue
                $this->load->view('front/view_layout', $data);
            endif;

        else:
            // Encapsulation des données
            $data['query']       = $data['query']->result();
            $data['title']       = $row->r_title;
            $data['meta_title']  = $data['title'];
            $data['meta_desc']   = $row->r_description;

            $data['all_content'] = $this->model_blog->get_all_content();

            // Chargement des données dans la vue
            $this->load->view('front/view_layout', $data);
        endif;
    }

}
