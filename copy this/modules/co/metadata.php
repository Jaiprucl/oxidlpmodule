<?php
/**
 *    This file is part of co
 *
 * @author    Christopher Olhoeft
 */

/**
 * Metadata version
 */
$sMetadataVersion = '0.2';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'co',
    'title'        => 'Landingpage Tool',
    'description'  => 'Ein Tool um Landingpages schnell und einfach erstellen zu koennen.',
    'thumbnail'    => 'co.png',
    'version'      => '0.2',
    'author'       => 'Christopher Olhoeft',
    'url'          => 'https://www.jumbo-discount.de/',
    'email'        => 'c.olhoeft@jumbo-discount.de',
    'extend'       => array(
        'landingpages'      => 'co/views/landingpages',
    ),
    'files' => array(
        'co_seo' 			=> 'co/admin/co_seo.php',
        'co_lpactions_seo' 	=> 'co/admin/co_object_seo.php',
		'co_lpactions_main'  => 'co/admin/co_lpmain.php',
        'co_lpactions_list'  => 'co/admin/co_lplist.php',
        'co_actions'     	=> 'co/admin/co_actions.php',
        'co_admindetails'    => 'co/admin/co_admindetails.php',
        'landingpages'     		=> 'co/views/landingpages.php',
    ),
    'templates' => array(
        'co_actions.tpl' => 'co/out/admin/tpl/co_actions.tpl',
        'co_seo.tpl' 	=> 'co/out/admin/tpl/co_seo.tpl',
        'landingpages.tpl' 	=> 'co/out/tpl/landingpages.tpl',
        'co_lpactions_list.tpl' => 'co/out/admin/tpl/co_lpactions_list.tpl',
        'co_lpactions_main.tpl' => 'co/out/admin/tpl/co_lpactions_main.tpl',
    ),
);