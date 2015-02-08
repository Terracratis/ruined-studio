<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	// MySQL config
	$config_mysql['host'] = 'localhost';
	$config_mysql['database'] = 'ruinedstudio_ltdish';
	$config_mysql['username'] = 'root';
	$config_mysql['password'] = '';
	// Page config
	$config_page['url'] = 'http://localhost';
	$config_page['url_folder'] = 'ruined-studio';
	$config_page['url_studio'] = '';
	$config_page['theme'] = 'display';
	$config_page['title'] = 'Ruined Studio';
	$config_page['index_title'] = 'Tūrinio valdymo sistemos, programos, žaidimai';
	// Seo config
	$config_seo['description'] = 'Ruined Studio presents';
	$config_seo['keywords'] = 'cms, tvs, online, games, it, php, mysql, žaidimai, programos, eshop';
	$config_seo['robots'] = 'index, follow';
	$config_seo['author'] = 'By Ruined Studio';
	$config_seo['charset'] = 'UTF-8';
	// Language
	$config_lang['default'] = 'en';
	$config_lang['available'] = array('en','lt','ru');
	// Facebook config
	$config_facebook['image'] = $config_page['data'].'/images/facebook_logo.png';
	$config_facebook['type'] = 'website';
	
	$ruinedstudio = true;
	$cookie_name = "order_liste";
	$config_set['min_order'] = 15;
?>