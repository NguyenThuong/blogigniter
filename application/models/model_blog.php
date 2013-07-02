<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_blog extends CI_Model{

    function get_all_rubriques()
    {
        $this->db->distinct('r_title')->from('rubrique')->order_by('r_title', 'ASC');
        $query_all_rubriques = $this->db->get();
        if( $query_all_rubriques->num_rows() > 0 ):
            return $query_all_rubriques->result();
        else:
            return false;
        endif;
    }

    function get_rubrique($slug_rubrique)
    {
        $this->db->select('c_title, c_content, c_cdate, c_image, c_url_rw, r_title, r_description, r_url_rw')->from('content')->join('rubrique', 'rubrique.r_id = content.r_id')->where('rubrique.r_url_rw', $this->uri->segment(1))->order_by('content.r_id', 'desc');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ):
            return $query;
        else:
            return false;
        endif;  
    }

    function get_all_content()
    {
        $this->db->select('*')->from('content')->join('rubrique', 'rubrique.r_id = content.r_id')->order_by('c_id', 'DESC');
        $query = $this->db->get();
        if( $query->num_rows() > 0 ):
            return $query->result();
        else:
            return false;
        endif;
    }

    function get_content($slug_content)
    {
        $this->db->select('c_title, c_content, c_image, c_cdate, c_url_rw, r_title, r_url_rw')->from('content')->join('rubrique', 'content.r_id = rubrique.r_id')->where('c_url_rw', $slug_content);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ):
            return $query;
        else:
            return false;
        endif;
    }

    function get_others_content($slug_content)
    {
        $this->db->select('c_title, c_url_rw, r_url_rw')->join('rubrique', 'rubrique.r_id = content.r_id')->from('content')->where('c_url_rw <>', $slug_content)->order_by('c_id', 'desc');
        $query_others = $this->db->get();
        if($query_others->num_rows() > 0 ):
            return $query_others->result();
        else:
            return false;
        endif;
    }

    function get_same_rubrique($slug_rubrique, $slug_content)
    {
        $this->db->select('c_title, c_url_rw, r_url_rw')->join('rubrique', 'content.r_id = rubrique.r_id')->from('content')->where('rubrique.r_url_rw', $slug_rubrique)->where('content.c_url_rw <>', $slug_content)->order_by('c_id', 'desc');
        $query_same_rubrique = $this->db->get();

        if($query_same_rubrique->num_rows() > 0 ):
            return $query_same_rubrique->result();
        else:
            return false;
        endif;
    }

}
