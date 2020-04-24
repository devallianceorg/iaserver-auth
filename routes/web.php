<?php
$router->get('/', 'HomeCtrl@index');

// LOGIN SOCIAL
$router->group(['prefix' => 'social'], function($router)
{
	$router->group(['middleware' => 'auth:social'], function($router) {
		$router->get('/me', 'SocialController@me');
	});

	$router->get('/{driver}', 'SocialController@login');
	$router->get('/{driver}/callback', 'SocialController@callback');
});

// LOGIN CON CREDENCIALES
$router->post('/login', 'AuthController@login');

$router->group(['middleware' => 'auth:api'], function($router)
{
	$router->get('/logout', 'AuthController@logout');
	$router->get('/refresh', 'AuthController@refresh');
	$router->get('/me', 'AuthController@me');
});


// ACL ADMIN , GESTION SOLO POR ROL:SUPERADMIN
// El permiso "gestion_acl" deberia ingresar a esta ruta
//$router->get('/pwd', 'AuthController@generateHash');
$router->group(['prefix' => 'acl','middleware' => ['auth:api']], function($router)
{
	$router->get('/hash', 'AuthController@generateHash');

	$router->group(['prefix' => 'users'], function($router)
	{
		$router->get('/{user_id}', 'UsersCrud@show');

		// Gestiona permisos
		$router->get('/', 'UsersCrud@all');
		$router->post('/', 'UsersCrud@add');
		$router->put('/', 'UsersCrud@update');
		$router->delete('/', 'UsersCrud@delete');
	});

	$router->group(['prefix' => 'role'], function($router)
	{
		// Gestiona roles
		$router->get('/', 'RoleCrud@all');
		$router->post('/', 'RoleCrud@add');
		$router->delete('/', 'RoleCrud@delete');

		// Gestiona permisos en roles
		$router->get('/{role}', 'RoleCrud@view');
		$router->post('/{role}', 'RoleCrud@updatePermission');

		// Gestiona relacion de roles y usuarios
		$router->get('/{role}/{userId}', 'RoleCrud@roleToUser');
	});

	$router->group(['prefix' => 'permission'], function($router)
	{
		// Gestiona permisos
		$router->get('/', 'PermissionCrud@all');
		$router->post('/', 'PermissionCrud@add');
		$router->delete('/', 'PermissionCrud@delete');
	});
});
