<?php
if ( ! function_exists('css_url'))
{
	function css_url($nom)
	{
		return '<link rel="stylesheet" href="' . base_url() . 'assets/css/' . $nom . '.css" />
';
	}
}

if ( ! function_exists('js_url'))
{
	function js_url($nom)
	{
		return '<script src="' . base_url() . 'assets/js/' . $nom . '.js"></script>
';
	}
}

if ( ! function_exists('content_url'))
{
	function content_url($rubric, $content, $titre)
	{
		return '<a href="' . base_url($rubric . '/' . $content) . '">' . $titre . '</a>';
	}
}

if ( ! function_exists('content_url_button'))
{
	function content_url_button($rubric, $content)
	{
		return '<a href="' . base_url($rubric . '/' . $content) . '" class="btn btn-primary">Lire la suite</a>';
	}
}

if ( ! function_exists('rubric_url'))
	{
	function rubric_url($rubric, $titre)
	{
		return '<a href="' . base_url($rubric) . '">' . $titre . '</a>';
	}
}

if ( ! function_exists('date_fr'))
{
	function date_fr ($jour, $mois, $annee)
	{
		$mois_n = $mois;
		switch ($mois) {
			case '01':
				$mois = 'Janvier';
				break;
			case '02':
				$mois = 'Février';
				break;
			case '03':
				$mois = 'Mars';
				break;
			case '04':
				$mois = 'Avril';
				break;
			case '05':
				$mois = 'Mai';
				break;
			case '06':
				$mois = 'Juin';
				break;
			case '7':
				$mois = 'Juillet';
				break;
			case '8':
				$mois = 'Aout';
				break;
			case '9':
				$mois = 'Septembre';
				break;
			case '10':
				$mois = 'Octobre';
				break;
			case '11':
				$mois = 'Novembre';
				break;
			case '12':
				$mois = 'Décembre';
				break;
			
			default:
				break;
		}

		return '<time datetime="' . $annee . '-' . $mois_n . '-' . $jour . '">' .$jour . ' ' . $mois . ' ' . $annee.'</time>';
	}
}

if ( ! function_exists('pagination_custom'))
{
	function pagination_custom()
	{
		// Paramètres de configuration
		# Nombre d'articles par page
		$config['per_page']         = 3;
		# Lister les pages par numéro (page 1, page 2, etc...)
		$config['use_page_numbers'] = TRUE;

		# HTML entre les digits
		$config['full_tag_open']    = '<ul class="pagination">';
		$config['full_tag_close']   = '</ul><!--pagination-->';
		$config['num_tag_open']     = '<li>';
		$config['num_tag_close']    = '</li>';
		$config['cur_tag_open']     = '<li class="active"><span>';
		$config['cur_tag_close']    = '</span></li>';
		$config['next_tag_open']    = '<li>';
		$config['next_tag_close']   = '</li>';
		$config['prev_tag_open']    = '<li>';
		$config['prev_tag_close']   = '</li>';
		$config['first_tag_open']   = '<li style="display: none;">';
		$config['first_tag_close']  = '</li>';
		$config['last_tag_open']    = '<li style="display: none;">';
		$config['last_tag_close']   = '</li>';

		return $config;
	}
}
