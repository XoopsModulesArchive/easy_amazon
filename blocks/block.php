<?php

function b_select_menu()
{
    global $_SERVER, $_GET;

    if (!defined('_AM_DEFAULT_KEYWORD')) {
        define('_AM_DEFAULT_KEYWORD', 'xoops');
    }

    $keyword2 = '';

    $_booksjp = '';

    $_musicjp = '';

    $_classicaljp = '';

    $_dvdjp = '';

    $_videojp = '';

    $_electronicsjp = '';

    $_softwarejp = '';

    $_videogamesjp = '';

    $author = '';

    $artist = '';

    $director = '';

    $_dvdjp = '';

    $_videojp = '';

    $keyword = (isset($_GET['keyword'])) ? htmlspecialchars(strip_tags($_GET['keyword']), ENT_QUOTES | ENT_HTML5) : _AM_DEFAULT_KEYWORD;

    $media = (isset($_GET['media'])) ? strip_tags($_GET['media']) : 'books-jp';

    if ($media) {
        $str = str_replace('-', '', $media);

        $_[$str] = ' selected';
    } else {
        $_booksjp = ' selected';
    }

    $block = [];

    $block['main'] = '
        <tr>
            <td>
            <div class="blockContent" style="text-align:center;">' . _MB_UAM_PICKUP . '</div>
            <form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST" style="font-size : 12px;">' . _MB_UAM_KEYWORD . '
            <input type="text" name="keyword" size="25" value="' . $keyword . '"><br>
            <select name="sort">
                <option selected value="salesrank">' . _MB_UAM_BESTSELLER . '</option>
                <option value="pmrank">' . _MB_UAM_PMRANK . '</option>
                <option value="reviewrank">' . _MB_UAM_REVIEWRANK . '</option>
                <option value="pricerank">' . _MB_UAM_PRICERANK . '</option>
                <option value="inverse-pricerank">' . _MB_UAM_INVERSEPRICERANK . '</option>
                <option value="daterank">' . _MB_UAM_DATERANK . '</option>
            </select><br>
            <input type="hidden" name="block" value="Keyword">
            <input class="formButton" type="submit" name="doit" value="' . _MB_UAM_SEARCH . '"></form>
            <div style="font-size : 12px;">
            <form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST" style="font-size : 13px;">' . _MB_UAM_S_MEDIA . '
            <input type="text" name="keyword2" size="25" value="' . $keyword2 . '"><br>
            <select name="media">
                <option value="books-jp"' . $_booksjp . '>' . _MB_UAM_BOOKS . '</option>
                <option value="music-jp"' . $_musicjp . '>' . _MB_UAM_MUSIC . '</option>
                <option value="classical-jp"' . $_classicaljp . '>' . _MB_UAM_CLASSICAL . '</option>
                <option value="dvd-jp"' . $_dvdjp . '>' . _MB_UAM_DVD . '</option>
                <option value="video-jp"' . $_videojp . '>' . _MB_UAM_VIDEO . '</option>
                <option value="electronics-jp"' . $_electronicsjp . '>' . _MB_UAM_ELECTRONICS . '</option>
                <option value="software-jp"' . $_softwarejp . '>' . _MB_UAM_SOFTWARE . '</option>
                <option value="videogames-jp"' . $_videogamesjp . '>' . _MB_UAM_VIDEOGAMES . '</option>
            </select><br>
            <input type="hidden" name="block" value="b_media">
            <input class="formButton" type="submit" name="doit" value="' . _MB_UAM_SEARCH . '">
            </form>
            <form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST" style="font-size : 13px;">' . _MB_UAM_AUTHOR . '
            <input type="text" name="author" size="25" value="' . $author . '"><br>
            <input type="hidden" name="block" value="b_author">
            <input class="formButton" type="submit" name="doit" value="' . _MB_UAM_S_AUTHOR . '">
            </form>
            <form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST" style="font-size : 13px;">' . _MB_UAM_ARTIST . '
            <input type="text" name="artist" size="25" value="' . $artist . '"><br>
            <select name="music">
                <option value="music-jp" selected>' . _MB_UAM_MUSIC . '</option>
                <option value="classical-jp">' . _MB_UAM_CLASSICAL . '</option>
            </select><br>
            <input type="hidden" name="block" value="b_artist">
            <input class="formButton" type="submit" name="doit" value="' . _MB_UAM_S_ARTIST . '">
            </form>
            <form action="' . $_SERVER['SCRIPT_NAME'] . '" method="POST" style="font-size : 13px;">' . _MB_UAM_DIRECTOR . '
            <input type="text" name="director" size="25" value="' . $director . '"><br>
            <select name="media">
                <option value="dvd-jp"' . $_dvdjp . '>DVD</option>
                <option value="video-jp"' . $_videojp . '>е╙е╟ек</option>
            </select>
            <input type="hidden" name="block" value="b_director">
            <input class="formButton" type="submit" name="doit" value="' . _MB_UAM_S_DIRECTOR . '">
            </form>
<script type="text/javascript">
<!--
function u_search_evn(evt_type,media)
{
	var n = evt_type.selectedIndex;
	location.href = "' . $_SERVER['SCRIPT_NAME'] . '?mode=browse&amp;id=" + evt_type.options[n].value + "&amp;media="+media + "-jp";
}
// -->
</script>
<form name="books">' . _MB_UAM_SELECTOR . '
</form></div>
            </td>
        </tr>
';

    return $block;
}

function b_show_pickup()
{
    $a = dir(XOOPS_ROOT_PATH . '/modules/easy_amazon/cash/');

    $files = [];

    $block = [];

    while ($fname = $a->read()) {
        if (is_dir($fname) || '.' == $fname || '..' == $fname) {
            continue;
        }

        if (eregi('block_', $fname)) {
            $files[] = $fname;
        }
    }

    $a->close();

    mt_srand((float) microtime() * 123456);

    shuffle($files);

    $block['unadon'] = '<span style="font-size:11px;text-align:center;"><a href="http://u-u-club.ddo.jp/~XOOPS/">@unadon</a>';

    $fp = fopen(XOOPS_ROOT_PATH . '/modules/easy_amazon/cash/' . $files[0], 'rb') or exit;

    $serialized_me = fread($fp, filesize(XOOPS_ROOT_PATH . '/modules/easy_amazon/cash/' . $files[0]));

    fclose($fp);

    $block['detail'] = base64_decode($serialized_me, true);

    return $block;
}
