<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'front/blog';

# 404
//$route['erreur404'] 	= $route['default_controller'] . '/erreur404';
//$route['404_override'] = 'front/error404';



/*--------------- ADMIN ---------------*/

# ADMIN (connection)
$route['admin']				   = 'admin/admin';
$route['admin/logout'] 		   = 'admin/admin/logout';
$route['admin/reset_password'] = 'admin/admin/reset_password';

# ADMIN content
$route['admin/content']				  = 'admin/content';
$route['admin/content/edit']		  = 'admin/content/edit';
$route['admin/content/edit/(:num)']   = 'admin/content/edit/$1';
$route['admin/content/delete/(:num)'] = 'admin/content/delete/$1';

# ADMIN rubric
$route['admin/rubric']				 = 'admin/rubric';
$route['admin/rubric/edit']			 = 'admin/rubric/edit';
$route['admin/rubric/edit/(:num)']   = 'admin/rubric/edit/$1';
$route['admin/rubric/delete/(:num)'] = 'admin/rubric/delete/$1';

# ADMIN tags
$route['admin/tags']			   = 'admin/tags';
$route['admin/tags/edit']		   = 'admin/tags/edit';
$route['admin/tags/edit/(:num)']   = 'admin/tags/edit/$1';
$route['admin/tags/delete/(:num)'] = 'admin/tags/delete/$1';
$route['admin/tags/content(:num)'] = 'admin/tags/$1';

# ADMIN comments
$route['admin/comments']					= 'admin/comments';
$route['admin/comments/moderate/(:num)']	= 'admin/comments/moderate/$1';
$route['admin/comments/desactivate/(:num)'] = 'admin/comments/desactivate/$1';
$route['admin/comments/delete/(:num)']		= 'admin/comments/delete/$1';

# ADMIN users
$route['admin/user']				 = 'admin/user';
$route['admin/user/edit']			 = 'admin/user/edit';
$route['admin/user/edit/(:num)']	 = 'admin/user/edit/$1';
$route['admin/user/delete/(:num)']   = 'admin/user/delete/$1';
$route['admin/user/change_password'] = 'admin/user/change_password';
$route['admin/user/(:num)']			 = 'admin/user/author/$1';

# ADMIN media
$route['admin/medias']		  = 'admin/medias';
$route['admin/medias/upload'] = 'admin/medias/upload';

# ADMIN params
$route['admin/params'] = 'admin/params';
$route['admin/search'] = 'admin/params/search';



/*--------------- FRONT ---------------*/

# FRONT contact
$route['contact'] = 'front/contact';
$route['toto'] = 'front/blog/toto';

# FRONT RSS
$route['rss'] = "front/feed";

# FRONT Search
$route['s'] = $route['default_controller'] . '/search';

# FRONT Author
$route['auteur/(:any)'] = $route['default_controller'] . '/auteur/$1';

# FRONT Tag
$route['t'] = $route['default_controller'] . '/tags';

# FRONT Pagination home
$route['page/(:num)'] 	= $route['default_controller'] . '/index/$1';

# FRONT Rubric
$route['(:any)']        = $route['default_controller'] . '/view/$1';

# FRONT Rubric + Content
$route['(:any)/(:any)'] = $route['default_controller'] . '/view/$1/$2';



/* End of file routes.php */
/* Location: ./application/config/routes.php */