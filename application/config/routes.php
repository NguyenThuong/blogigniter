<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'front/blog';

# 404
//$route['erreur404'] 	= $route['default_controller'] . '/erreur404';
$route['404_override'] = '';

#admin
$route['admin']           = 'admin/admin';
$route['admin/logout'] 	  = 'admin/admin/logout';
$route['admin/dashboard'] = 'admin/dashboard';

# ADMIN content
$route['admin/dashboard/edit'] 			= 'admin/dashboard/edit';
$route['admin/dashboard/edit/(:num)'] 	= 'admin/dashboard/edit/$1';
$route['admin/dashboard/delete/(:num)'] = 'admin/dashboard/delete/$1';
$route['admin/dashboard/author']		= 'admin/dashboard/author';

$route['admin/dashboard/author/(:num)'] = 'admin/dashboard/author/$1';
$route['admin/dashboard/params']		= 'admin/dashboard/params';
$route['admin/dashboard/search']		= 'admin/dashboard/search';

# ADMIN rubric
$route['admin/dashboard/rubric'] 			   = 'admin/dashboard/rubric';
$route['admin/dashboard/edit_rubric'] 		   = 'admin/dashboard/edit_rubric';
$route['admin/dashboard/edit_rubric/(:num)']   = 'admin/dashboard/edit_rubric/$1';
$route['admin/dashboard/delete_rubric/(:num)'] = 'admin/dashboard/delete_rubric/$1';

# ADMIN comments
$route['admin/dashboard/comment']					 = 'admin/dashboard/comment';
$route['admin/dashboard/moderate_comment/(:num)'] 	 = 'admin/dashboard/moderate_comment/$1';
$route['admin/dashboard/desactivate_comment/(:num)'] = 'admin/dashboard/desactivate_comment/$1';
$route['admin/dashboard/delete_comment/(:num)']		 = 'admin/dashboard/delete_comment/$1';

# ADMIN users
$route['admin/dashboard/users']				 = 'admin/dashboard/users';
$route['admin/dashboard/edit_user']			 = 'admin/dashboard/edit_user';
$route['admin/dashboard/edit_user/(:num)']	 = 'admin/dashboard/edit_user/$1';
$route['admin/dashboard/delete_user/(:num)'] = 'admin/dashboard/delete_user/$1';
$route['admin/dashboard/change_password']	 = 'admin/dashboard/change_password';

$route['admin/dashboard/search']			 = 'admin/dashboard/search';

# ADMIN gallery
$route['admin/dashboard/gallery'] = 'admin/dashboard/gallery';
$route['admin/dashboard/upload']  = 'admin/dashboard/upload';

# RSS
$route['rss'] = "front/feed";

# Search
$route['s'] = $route['default_controller'] . '/search';

$route['auteur/(:any)'] = $route['default_controller'] . '/auteur/$1';

# pagination home
$route['page/(:num)'] 	= $route['default_controller'] . '/index/$1';

# rubrique
$route['(:any)']        = $route['default_controller'] . '/view/$1';

# rubrique + content
$route['(:any)/(:any)'] = $route['default_controller'] . '/view/$1/$2';



/* End of file routes.php */
/* Location: ./application/config/routes.php */