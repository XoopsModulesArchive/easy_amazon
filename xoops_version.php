<?php

$modversion['name'] = _MI_U_AMAZON_NAME;
$modversion['version'] = '0.90';
$modversion['description'] = _MI_U_AMAZON_DESC;
$modversion['credits'] = "<a href='http://u-u-club.ddo.jp/~XOOPS/' target='_blank'>xoops@unadon</a>";
$modversion['author'] = 'xoops@unadon';
$modversion['help'] = '';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'logo.gif';
$modversion['dirname'] = 'easy_amazon';

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!

// Tables created by sql file (without prefix!)

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminmenu'] = 'admin/menu.php';
$modversion['adminindex'] = 'admin/index.php';

// Menu
$modversion['hasMain'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'easy_amazon_index.html';
$modversion['templates'][1]['description'] = 'easy amazon';

// Blocks
$modversion['blocks'][1]['file'] = 'block.php';
$modversion['blocks'][1]['name'] = 'Search Tools';
$modversion['blocks'][1]['description'] = 'block select menu';
$modversion['blocks'][1]['show_func'] = 'b_select_menu';
$modversion['blocks'][1]['template'] = 'select_menu_.html';
$modversion['blocks'][2]['file'] = 'block.php';
$modversion['blocks'][2]['name'] = 'Amazon Web Service';
$modversion['blocks'][2]['description'] = 'block Amazon Web Service';
$modversion['blocks'][2]['show_func'] = 'b_show_pickup';
$modversion['blocks'][2]['template'] = 'am_pickup.html';

// Config Settings (only for modules that need config settings generated automatically)
$modversion['config'][1]['name'] = 'amazon_tag';
$modversion['config'][1]['title'] = '_MI_U_AMAZON_TAG';
$modversion['config'][1]['description'] = '_MI_U_AMAZON_TAG_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'webservices-20';

$modversion['config'][2]['name'] = 'amazon_token';
$modversion['config'][2]['title'] = '_MI_U_AMAZON_TOKEN';
$modversion['config'][2]['description'] = '_MI_U_AMAZON_TOKEN_DESC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = 'D14U31V3KG3NV5';

$modversion['config'][3]['name'] = 'easy_amazon_cachepath';
$modversion['config'][3]['title'] = '_MI_U_AMAZON_CACHEPATH';
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = XOOPS_ROOT_PATH . '/modules/easy_amazon/cash/';

$modversion['config'][4]['name'] = 'easy_amazon_wsdl';
$modversion['config'][4]['title'] = '_MI_U_AMAZON_WSDL';
$modversion['config'][4]['description'] = '_MI_U_AMAZON_WSDL_DESC';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = XOOPS_ROOT_PATH . '/modules/easy_amazon/class/AmazonWebServices.wsdl';

$modversion['config'][5]['name'] = 'easy_amazon_file_mtime';
$modversion['config'][5]['title'] = '_MI_U_AMAZON_FILE_MTIME';
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = '24';

$modversion['config'][6]['name'] = 'easy_amazon_cash_clear';
$modversion['config'][6]['title'] = '_MI_U_AMAZON_CASH_CLEAR_TIME';
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = '72';

$modversion['config'][7]['name'] = 'easy_amazon_showpages';
$modversion['config'][7]['title'] = '_MI_U_AMAZON_SHOWPAGES';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 5;
$modversion['config'][7]['options'] = ['5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10];
/*
$modversion['config'][8]['name'] = 'easy_amazon_default_keyword';
$modversion['config'][8]['title'] = '_MI_U_AMAZON_DEFAULT_KEYWORD';
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = 'xoops';
*/