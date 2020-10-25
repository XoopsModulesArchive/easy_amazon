<?php

include './header.php';
include XOOPS_ROOT_PATH . '/header.php';

define('AMAZON_LOCALE', 'jp');						//AMAZONロケール
define('IMAGE_SIZE', 1);								//画像サイズを取得:0 取得しない:その他1-9
if (!defined('_AM_DEFAULT_KEYWORD')) {
    define('_AM_DEFAULT_KEYWORD', 'xoops');
}
define('_U_AM_DEFAULT_AUTHOR', '樋口一葉');
define('_U_AM_DEFAULT_DIRECTOR', '黒沢');
define('_AUTHOR', 'Script :: <a href="http://u-u-club.ddo.jp/~XOOPS/">xoopc@unadon</a>');
setlocale(LC_TIME, 'ja_JP');

require_once XOOPS_ROOT_PATH . '/modules/easy_amazon/class/am_functions.class.php';
require_once XOOPS_ROOT_PATH . '/modules/easy_amazon/class/uuamazon.php';

if (isset($_GET['mode']) && 'open_pic' == $_GET['mode']) {
    am_open_picture(strip_tags($_GET['target_img']), (int)$_GET['width'], (int)$_GET['height']);

    exit;
}

if (empty($xoopsModule) || 'easy_amazon' != $xoopsModule->getVar('dirname')) {
    require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';

    $moduleHandler = xoops_getHandler('module');

    $module = $moduleHandler->getByDirname('easy_amazon');

    $configHandler = xoops_getHandler('config');

    $config_array = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
} else {
    $config_array = $xoopsModuleConfig;
}

if (!isset($config_ver_check['easy_amazon_cachepath'])) {
    $config_array['easy_amazon_cachepath'] = XOOPS_ROOT_PATH . '/modules/easy_amazon/cash/';

    $config_array['amazon_tag'] = 'webservices-20';

    $config_array['amazon_token'] = 'D14U31V3KG3NV5';

    $config_array['easy_amazon_wsdl'] = XOOPS_ROOT_PATH . '/modules/easy_amazon/class/AmazonWebServices.wsdl';

    $config_array['easy_amazon_showpages'] = 5;

    $config_array['easy_amazon_file_mtime'] = 24;

    $config_array['easy_amazon_cash_clear'] = 72;
}
define('_U_AM_TAG', $config_array['amazon_tag']);
define('_U_AM_TOKEN', $config_array['amazon_token']);
define('_U_AM_FILE_DIR', $config_array['easy_amazon_cachepath']);
define('WSDL_FILE', $config_array['easy_amazon_wsdl']);
define('_U_AM_FILE_MTIME', $config_array['easy_amazon_file_mtime']);
define('_U_AM_CASH_CLEAR_TIME', $config_array['easy_amazon_cash_clear']);
define('_U_AM_PAGE_ON_DETAILS', $config_array['easy_amazon_showpages']);

$AMAZON = new am_functions();

$keyword = (isset($_GET['keyword'])) ? htmlspecialchars(strip_tags($_GET['keyword']), ENT_QUOTES | ENT_HTML5) : _AM_DEFAULT_KEYWORD;
$media = (isset($_GET['media'])) ? strip_tags($_GET['media']) : 'books-jp';
$s_page = (isset($_GET['p']) && ctype_digit($_GET['p'])) ? (int)$_GET['p'] : 1;
$am_sort = 'salesrank';
$main = '';
if ($_POST) {
    $NEW_P = array_map(create_function('$a', 'return trim( strip_tags( $a ) );'), $_POST);

    $NEW_P = array_filter($NEW_P, create_function('$a', 'if ( ! $a ) return false;return true;'));
}

$GLOBALS['xoopsOption']['template_main'] = 'easy_amazon_index.html';

switch (true) {
case (isset($_GET['mode']) && 'asin' == $_GET['mode']):
    $main = $AMAZON->asin(trim(strip_tags($_GET['asin'])), $media);
    $xoopsTpl->assign('main', $main);
    break;
case (isset($NEW_P['doit']) && 'Keyword' == $NEW_P['block']):
    $am_sort = ($NEW_P['sort']) ?: 'salesrank';
    if (isset($NEW_P['keyword'])) {
        $keyword = htmlspecialchars($NEW_P['keyword'], ENT_QUOTES | ENT_HTML5);
    }
    $main = $AMAZON->am_make_amazon($keyword, $media, $type = 'heavy', $s_page = 1, $am_sort, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($NEW_P['doit']) && 'b_media' == $NEW_P['block']):
    $am_sort = ($NEW_P['sort']) ?: 'salesrank';
    $keyword2 = (isset($NEW_P['keyword2'])) ? htmlspecialchars($NEW_P['keyword2'], ENT_QUOTES | ENT_HTML5) : _AM_DEFAULT_KEYWORD;
    $media = $NEW_P['media'] ?? 'books-jp';
    $main = $AMAZON->am_make_amazon($keyword2, $media, $type = 'heavy', $s_page = 1, $am_sort, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($NEW_P['doit']) && 'b_author' == $NEW_P['block']):
    $media = 'books-jp';
    $am_sort = 'salesrank';
    $keyword = (isset($NEW_P['author'])) ? htmlspecialchars($NEW_P['author'], ENT_QUOTES | ENT_HTML5) : _U_AM_DEFAULT_AUTHOR;
    $main = $AMAZON->am_make_amazon_asin($author, $media, 'author', $author, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($_POST['doit']) && 'manufacturer' == $_POST['doit']):
    $am_sort = (isset($_GET['sort'])) ? strip_tags($_GET['sort']) : 'salesrank';
    $manufacturer = htmlspecialchars(strip_tags($_GET['manufacturer']), ENT_QUOTES | ENT_HTML5);
    $keyword = $manufacturer;
    $main = $AMAZON->am_make_amazon_asin($manufacturer, $media, 'manufacturer', $manufacturer, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($NEW_P['doit']) && 'b_artist' == $NEW_P['block']):
    $media = $NEW_P['music'] ?? 'music-jp';
    $am_sort = 'salesrank';
    if (isset($NEW_P['author'])) {
        $keyword = htmlspecialchars($NEW_P['author'], ENT_QUOTES | ENT_HTML5);
    } else {
        $keyword = ($NEW_P['artist']) ? htmlspecialchars($NEW_P['artist'], ENT_QUOTES | ENT_HTML5) : 'BoA';
    }
    $main = $AMAZON->am_make_amazon_asin($artist, $media, 'artist', $artist, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($NEW_P['doit']) && 'b_director' == $NEW_P['block']):
    $media = $NEW_P['media'] ?? 'dvd-jp';
    $am_sort = 'salesrank';
    if ($NEW_P['author']) {
        $keyword = htmlspecialchars($NEW_P['author'], ENT_QUOTES | ENT_HTML5);
    } else {
        $keyword = (isset($NEW_P['artist'])) ? htmlspecialchars($NEW_P['artist'], ENT_QUOTES | ENT_HTML5) : _U_AM_DEFAULT_DIRECTOR;
    }
    $keyword = preg_replace("/\x81\x40/", ' ', $keyword);
    $main = $AMAZON->am_make_amazon_asin($director, $media, 'director', $director, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($_GET['mode']) && 'am' == $_GET['mode']):
    $am_sort = (isset($_GET['sort'])) ? strip_tags($_GET['sort']) : 'salesrank';
    $keyword = (isset($_GET['keyword'])) ? htmlspecialchars(strip_tags(rawurldecode($_GET['keyword'])), ENT_QUOTES | ENT_HTML5) : _AM_DEFAULT;
    $main = $AMAZON->am_make_amazon($keyword, $media, $type = 'heavy', $s_page, $am_sort, $cash = 'OFF');
    $xoopsTpl->assign('main', $main);
    break;
case (isset($_GET['mode']) && 'browse' == $_GET['mode']):
    if (ctype_digit($_GET['id'])) {
        $asin = $_GET['id'];

        $main = $AMAZON->am_make_amazon_asin($asin, $media, 'browse_node', $cash = '');
    } else {
        $main = $AMAZON->am_make_amazon();
    }
    $xoopsTpl->assign('main', $main);
    break;
case (isset($_GET['mode']) && 'sim' == $_GET['mode']):
    $asin = trim(strip_tags($_GET['id']));
    $AMAZON->similaritysearch($asin);
    $main = $AMAZON->am_make_table($s_page = 1, _U_AM_LIKENESS, $qmode, $media, $am_sort = 'salesrank');
    $xoopsTpl->assign('main', $main);
    break;
default:
    $xoopsTpl->assign('main', $AMAZON->rand_open());
}

include XOOPS_ROOT_PATH . '/footer.php';

    function am_open_picture($target_img, $width, $height)
    {
        header('Content-type: text/html; charset=EUC-JP');

        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>AMAZON</title>
	<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<link rel="stylesheet" href="topimg/newtop.css" type="text/css">
	</head>
	<body>
	<div style="font-size : 13px; text-align : center;">' . _U_AM_CLOSE_PIC . '<br><a href="javascript:void( 0 );" onclick="window.close()"><img src="' . $target_img . '" width="' . $width . '" height="' . $height . '" alt="' . _U_AM_BIG_PIC . '" border="0"></a></div>
	<p style="font-size : 13px; text-align : center;">' . _U_AM_CLOSE . '&nbsp;<a href="javascript:void( 0 );" onclick="window.close()"><b style="background-color : #DDDDDD;">' . _U_AM_CLOSE2 . '</b></a></p>
	</body>
	</html>';
    }
