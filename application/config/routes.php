<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'users/products';


/**
 * Users routes collection
 */
$route['register'] = 'users/register';
$route['login'] = 'users/login';


/**
 * Admin routes collection
 */
$route['admin'] = 'admins/login';
$route['admin/logout'] = 'admins/logout';

/**
 * Users - Products routes collection
 */
$route['products'] = 'users/products';
$route['products/show/(:any)']['get'] = 'users/show/$1';


/**
 * Admin - Products routes collection
 */
$route['dashboard'] = 'orders/get_orders_list';
$route['dashboard/orders'] = 'orders/get_orders_list';
$route['dashboard/orders/(:any)']['get'] = 'orders/order_list_paginate/$1';
$route['orders/show/(:any)'] = 'orders/show/$1';

$route['dashboard/products'] = 'products/products_list';
$route['products/edit_product/(:any)']['get'] = 'products/edit_product/$1';

$route['products/update_product/(:any)'] = 'products/update_product/$1';

$route['products/product_list_paginate/(:any)']['get'] = 'products/product_list_paginate/$1';

$route['products/delete_product/(:any)']['get'] = 'products/delete_product/$1';
$route['products/update_category/(:any)'] = 'products/update_category/$1';
$route['products/delete_category/(:any)']['get'] = 'products/delete_category/$1';

/**
 * Orders - User routes collection
 */
$route['carts'] = 'orders/carts';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
