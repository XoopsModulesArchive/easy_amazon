<?php

require_once './class/uuamazon.php';

class am_functions extends AmazonSearch
{
    public $cash_dir = _U_AM_FILE_DIR;
    public $fname    = '';
    public $ftime    = _U_AM_FILE_MTIME;
    public $page     = _U_AM_PAGE_ON_DETAILS;
    public $c_time   = _U_AM_CASH_CLEAR_TIME;

    public function __construct()
    {
        parent::__construct();
    }

    public function am_cash_clear()
    {
        $a = dir($this->cash_dir);
        while ($fname = $a->read()) {
            if (preg_match('/(.cash)$/i', $fname)
                && filectime($fname) > time() - $this->c_time * 3600) {
                @unlink($fname);
            }
        }
        $a->close();
    }

    public function rand_open()
    {
        srand((double)microtime() * 123456);
        $j = rand(0, 15);
        switch ($j) {
            case 0:
                return $this->am_make_amazon_asin(465610, 'books-jp', 'browse_node', $key = '');
            case 1:
                return $this->am_make_amazon_asin(3839151, 'kitchen-jp', 'browse_node', $key = '');
            case 2:
                return $this->am_make_amazon_asin(562032, 'music-jp', 'browse_node', $key = '');
            case 3:
                return $this->am_make_amazon_asin(562002, 'dvd-jp', 'browse_node', $key = '');
            case 4:
                $this->am_cash_clear();
                return $this->am_make_amazon_asin(3210991, 'electronics-jp', 'browse_node', $key = '');
            case 5:
                return $this->am_make_amazon_asin(637872, 'videogames-jp', 'browse_node', $key = '');
            case 6:
                return $this->am_make_amazon(_AM_DEFAULT_KEYWORD, 'books-jp', $type = 'heavy', $s_page = 1, 'salesrank', $cash = '');
            default:
                return $this->am_make_amazon_asin(465610, 'books-jp', 'browse_node', $key = '');
        }
    }

    public function am_make_amazon_asin($asin, $mode, $func, $key = '', $cash = '')
    {
        $this->fname = $this->cash_dir . $asin . $func . '.cash';
        if (!file_exists($this->fname) || !$this->am_what_new($this->fname)) {
            $this->do_search($func, $asin, $keyword = $key, $mode, $type = 'heavy', $s_page = 1, $am_sort = 'salesrank');
            $qmode = $this->am_get_media($mode);
            $am_tb = $this->am_make_table($s_page, $key, $qmode, $mode, $am_sort);
            if ($cash != 'OFF') {
                $this->am_make_cash($this->fname, $am_tb);
            }
        } else {
            $am_tb = $this->am_read_cash($this->fname);
        }
        return $am_tb;
    }

    public function am_make_amazon($keyword = _AM_DEFAULT, $mode = 'books-jp', $type = 'heavy', $s_page = 1, $am_sort = 'salesrank', $cash = '')
    {
        $this->fname = $this->cash_dir . md5($keyword) . $mode . $s_page . $am_sort . '.cash';
        $qmode       = $this->am_get_media($mode);
        if (!file_exists($this->fname) || !$this->am_what_new($this->fname)) {
            $this->keywordsearch($keyword, $mode, $type, $s_page, $am_sort);
            $am_tb = $this->am_make_table($s_page, $keyword, $qmode, $mode, $am_sort);
            if ($cash != 'OFF') {
                $this->am_make_cash($fname, $am_tb);
            }
        } else {
            $am_tb = $this->am_read_cash($fname);
        }
        return $am_tb;
    }

    public function am_make_cash($fname, $dat)
    {
        $fp = fopen($fname, 'w');
        flock($fp, LOCK_EX);
        fwrite($fp, base64_encode($dat));
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    public function am_read_cash($fname)
    {
        $fp = fopen($fname, 'r') or exit;
        $serialized_me = fread($fp, filesize($fname));
        fclose($fp);
        return base64_decode($serialized_me);
    }

    public function am_make_table($s_page, $keyword, $qmode, $mode, $am_sort)
    {
        global $_SERVER;
        $page  = $this->am_pages($this->Details['TotalPages'], $keyword, $mode, $am_sort);
        $am_tb = '
			<div class="odd" style="font-size : 10px;text-align: right;">' . _U_AM_KEYWORD . '<strong>' . $keyword . '</strong>&nbsp;' . _U_AM_MEDIA . $qmode . '&nbsp;' . _U_AM_VIEW_PAGE . $s_page . '<br>' . $page . '</div>
				<table cellspacing="0" cellpadding="0" width="95%" border="0" class="outer">
					<tbody>';

        for ($i = 0; $i < $this->page; $i++) {
            if (!isset($this->Details['Url'][$i])) {
                break;
            }
            $auth    = '';
            $im_size = '';
            $b_name  = $this->cash_dir . 'block_' . $this->Details['Asin'][$i] . '.cash';
            $size    = @getimagesize($this->Details['openImage'][$i]);
            $b_size  = @getimagesize($this->Details['medImage'][$i]);
            if ($i < 2) {
                if (!file_exists($b_name) || !$this->am_what_new($b_name)) {
                    $blockdata = '<a href="'
                                 . $this->Details['Url'][$i]
                                 . '" target="_blank"><span style="font-size:11px;"><img src="'
                                 . $this->Details['medImage'][$i]
                                 . '" '
                                 . $b_size[3]
                                 . ' alt="'
                                 . $this->Details['ProductName'][$i]
                                 . '" style="vertical-align : middle;margin:10px;border-style:none;"></a><br>'
                                 . $this->Details['ProductName'][$i]
                                 . '</span>';
                    $this->am_make_cash($b_name, $blockdata);
                }
            }

            if (isset($this->Details['Authors'][$i]) && preg_match('&nbsp;', $this->Details['Authors'][$i])) {
                $authors = explode('&nbsp;', $this->Details['Authors'][$i]);
                foreach ($authors as $author) {
                    $auth .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?doit=' . $mode . '&amp;author=' . rawurlencode($author) . '&amp;media=' . $mode . '&amp;sort=' . $am_sort . '">' . $author . '</a>' . _U_AM_AUTHOR . '&nbsp;';
                }
            } elseif (isset($this->Details['Authors'][$i])) {
                $auth = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?doit=' . $mode . '&amp;author=' . rawurlencode($this->Details['Authors'][$i]) . '&amp;media=' . $mode . '&amp;sort=' . $am_sort . '">' . $this->Details['Authors'][$i] . '</a>' . _U_AM_AUTHOR;
            }
            $am_tb   .= '
		                    <tr class="head">
		                        <th class="pagetitle" style="padding:5px;">' . $qmode . '¡§<a href="' . $this->Details['Url'][$i] . '" target="_blank">' . $this->Details['ProductName'][$i] . '</a></th>
		                    </tr>
		                    <tr>
		                    	<td style="padding:10px;"><div style="font-size : 13px;text-align : left;">';
            $am_tb   .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=asin&amp;asin=' . $this->Details['Asin'][$i] . '&amp;media=' . $mode . '">' . _U_AM_PICKUP . '</a><br>';
            $im_size = $this->am_img_check($this->Details['samllImage'][$i]);
            if ($im_size[0] > 10 && $im_size) {
                $am_tb .= '
			<a href="javascript:openPreviewWindow( \''
                          . $_SERVER['SCRIPT_NAME']
                          . '?mode=open_pic&amp;target_img='
                          . $this->Details['openImage'][$i]
                          . '&amp;width='
                          . $size[0]
                          . '&amp;height='
                          . $size[1]
                          . '\',\'pic_open\','
                          . ($size[0] + 100)
                          . ','
                          . ($size[1] + 100)
                          . ' );"><img src="'
                          . $this->Details['samllImage'][$i]
                          . '" '
                          . $im_size[3]
                          . ' alt="'
                          . $this->Details['ProductName'][$i]
                          . '" style="vertical-align : middle;float : right;margin:10px;border-style:none;"><br></a>';
            } elseif ($size[0] < 10 && $im_size[0] > 10) {
                $am_tb .= '<img src="' . $this->Details['samllImage'][$i] . '" ' . $im_size[3] . ' alt="' . $this->Details['ProductName'][$i] . '" style="vertical-align : middle;float : right;margin:10px;border-style:none;"><br>';
            }
            $am_tb .= $auth
                      . '<br>¡þ¡§<a href="'
                      . $_SERVER['SCRIPT_NAME']
                      . '?doit=manufacturer&amp;manufacturer='
                      . rawurlencode($this->Details['Manufacturer'][$i])
                      . '&amp;media='
                      . $mode
                      . '&amp;sort='
                      . $am_sort
                      . '">'
                      . $this->Details['Manufacturer'][$i]
                      . '</a><br>'
                      . _U_AM_EDITION
                      . $this->Details['Media'][$i]
                      . '</div>

				<div style="font-size : 14px;text-align : left;">'
                      . _U_AM_LISTPRICE
                      . $this->Details['ListPrice'][$i]
                      . '&nbsp;<strong>'
                      . _U_AM_OURPRICE
                      . '<span style="color : #C71585;">'
                      . $this->Details['OurPrice'][$i]
                      . '</span></strong></div>';

            if (isset($this->BrowseId[$i])) {
                $am_tb .= '
		        	<div style="font-size : 13px;text-align : left;">
			<span onclick="exMenu( \'browseId' . $i . '\' )" style="cursor:pointer;font-size : 12px;"><span style="color : #FF8C00;"></span>' . _U_AM_CATEGORY_SEARCH . '</span>
			<div style="display:none;font-size : 12px;text-align : left;" id="browseId' . $i . '">';
                foreach ($this->BrowseId[$i] as $key => $category) {
                    $category = mb_preg_replace("'._U_AM_CATEGORY.' - ", '', $category);
                    $am_tb    .= '
			¡¦<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=browse&amp;id=' . $key . '&amp;media=' . $mode . '">' . $category . '</a><br>';
                }
                $am_tb .= '</div>';
            }
            $am_tb .= '
	<form method="POST" action="http://www.amazon.co.jp/exec/obidos/dt/assoc/handle-buy-box=' . $this->Details['Asin'][$i] . '">
	<input type="hidden" name="asin.' . $this->Details['Asin'][$i] . '" value="1">
	<input type="hidden" name="tag_value" value="' . _U_AM_TAG . '">
	<input type="hidden" name="tag-value" value="' . _U_AM_TAG . '">
	<input type="hidden" name="dev-tag-value" value="' . _U_AM_TOKEN . '">
	<input type="submit" name="submit.add-to-cart" value="' . _U_AM_BUY_AMAZON . '">
	</form>
								</td>
		                    </tr>';
        }
        $am_tb .= '
		                        </td>
		                    </tr>
		                </tbody>
		            </table>';

        return $am_tb;
    }

    public function asin($asin, $media)
    {
        $this->fname = $this->cash_dir . $asin . $media . '.cash';
        $qmode       = $this->am_get_media($media);
        if (!file_exists($this->fname) || !$this->am_what_new($this->fname)) {
            $this->asinsearch($asin, $media, $type = 'heavy');
            if (isset($this->Details['fault'])) {
                return '<p style="font-size : 12px;text-align: center;">' . _U_AM_FAIL . '<br>
				<a href="javaScript:history.go( -1 )">' . _U_AM_GOBACK . '</a></p>';
            } else {
                $dat = $this->am_make_one(1, $qmode, $qmode, $media, 'salesrank', $asin);
                $this->am_make_cash($this->fname, $dat);
                return $dat;
            }
        } else {
            return $this->am_read_cash($this->fname);
        }
    }

    public function am_make_one($s_page, $keyword, $qmode, $mode, $am_sort, $asin)
    {
        $page    = $this->am_pages($this->Details['TotalPages'], $keyword, $mode, $am_sort);
        $im_size = '';
        $auth    = '';
        $size    = [];
        $am_tb   = '
		<div class="odd" style="font-size : 10px;text-align: right;">' . _U_AM_KEYWORD . '<strong>' . $keyword . '</strong>&nbsp;' . _U_AM_MEDIA . $qmode . '&nbsp;' . _U_AM_VIEW_PAGE . $s_page . '<br>' . $page . '</div>
		<table cellspacing="0" cellpadding="0" width="100%" border="0" class="outer">
			<tbody>';
        $size    = @getimagesize($this->Details['openImage'][0]);
        if (isset($this->Details['Authors'][0]) && preg_match('&nbsp;', $this->Details['Authors'][0])) {
            $authors = explode('&nbsp;', $this->Details['Authors'][0]);
            foreach ($authors as $author) {
                $auth .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?doit=' . $mode . '&amp;author=' . rawurlencode($author) . '&amp;media=' . $mode . '&amp;sort=' . $am_sort . '">' . $author . '</a>' . _U_AM_AUTHOR . '&nbsp;';
            }
        } elseif (isset($this->Details['Authors'][0]) && $this->Details['Authors'][0]) {
            $auth = '<a href="' . $_SERVER['SCRIPT_NAME'] . '?doit=' . $mode . '&amp;author=' . rawurlencode($this->Details['Authors'][0]) . '&amp;media=' . $mode . '&amp;sort=' . $am_sort . '">' . $this->Details['Authors'][0] . '</a>' . _U_AM_AUTHOR;
        }
        $am_tb   .= '
				<tr class="head">
					<th class="pagetitle" style="padding:5px;">' . $qmode . '¡§<a href="' . $this->Details['Url'][0] . '" target="_blank">' . $this->Details['ProductName'][0] . '</a></th>
				</tr>
				<tr>
					<td><div style="font-size : 13px;text-align : left;">';
        $im_size = $this->am_img_check($this->Details['medImage'][0]);
        if ($im_size[0] > 10 && $im_size) {
            $am_tb .= '<a href="javascript:openPreviewWindow( \''
                      . $_SERVER['SCRIPT_NAME']
                      . '?mode=open_pic&amp;target_img='
                      . $this->Details['openImage'][0]
                      . '&amp;width='
                      . $size[0]
                      . '&amp;height='
                      . $size[1]
                      . '\',\'pic_open\','
                      . ($size[0] + 100)
                      . ','
                      . ($size[1] + 100)
                      . ' );"><img src="'
                      . $this->Details['medImage'][0]
                      . '" '
                      . $im_size[3]
                      . ' alt="'
                      . $this->Details['ProductName'][0]
                      . '" style="vertical-align : middle;float : right;margin:10px;border-style:none;"></a><br>';
        } elseif ($size[0] < 10 && $im_size[0] > 10) {
            $am_tb .= '<img src="' . $this->Details['samllImage'][$i] . '" ' . $im_size[3] . ' alt="' . $this->Details['ProductName'][$i] . '" style="vertical-align : middle;float : right;border-style:none;"><br>';
        }
        $am_tb .= $this->Details['ProductDescription'] . '<br>' . $auth . '<br><b>' . $this->Details['Manufacturer'][0] . '</b><br>' . _U_AM_EDITION . $this->Details['Media'][0] . '</div>
			<div style="font-size : 13px;text-align : left;">' . _U_AM_LISTPRICE . $this->Details['ListPrice'][0] . '&nbsp;<strong>' . _U_AM_OURPRICE . '<span style="color : #C71585;">' . $this->Details['OurPrice'][0] . '</span></strong><br>
				' . _U_AM_SALESRANK . $this->Details['SalesRank'][0] . '<br>
				' . _U_AM_AVAILABILITY . $this->Details['Availability'][0] . '<br>';
        if (isset($this->BrowseId[0]) && $this->BrowseId[0]) {
            $am_tb .= '
			<span style="font-size : 12px;color : #FF8C00;">' . _U_AM_CATEGORY_SEARCH . '</span>
			<div style="font-size : 12px;text-align : left;">';
            foreach ($this->BrowseId[0] as $key => $category) {
                $category = mb_preg_replace("'._U_AM_CATEGORY_SEARCH.' - ", '', $category);
                $am_tb    .= '¡¦<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=browse&amp;id=' . $key . '&amp;media=' . $mode . '">' . $category . '</a><br>';
            }
            $am_tb .= '</div>';
        }
        $j     = 1;
        $am_tb .= '<br>' . _U_AM_SIMILARPRODUCTS;
        foreach ($this->SimilarProducts as $similarproducts) {
            if ($similarproducts == 2147483647) {
                continue;
            }
            $am_tb .= '(&nbsp;<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=sim&amp;id=' . $similarproducts . '&amp;media=' . $mode . '">' . $j . '</a>&nbsp;)&nbsp;';
            $j++;
        }
        $am_tb .= '<br>';
        if (isset($this->Details['Rating_0']) && $this->Details['Rating_0']) {
            $am_tb .= '<span style="font-size : 12px;color : #FF8C00;">' . _U_AM_CUSTOMERREVIEW . '</span>&nbsp;' . _U_AM_CUSTOMERREVIEWS . $this->Details['TotalCustomerReviews'][0] . '</div>
				<div style="font-size : 12px;text-align : left;">';
            foreach ($this->Details['Summary_0'] as $ckey => $summary) {
                $am_tb .= $this->am_get_reviewimage($this->Details['Rating_0'][$ckey]) . '
				<strong>¡Ú' . $summary . '¡Û</strong><br>
				<strong>' . _U_AM_COMMENT . '</strong><br>' . $this->Details['Comment_0'][$ckey] . '<br><br>';
            }
            $am_tb .= '</div>';
        }
        $am_tb .= '
	<form method="POST" action="http://www.amazon.co.jp/exec/obidos/dt/assoc/handle-buy-box=' . $asin . '">
	<input type="hidden" name="asin.' . $asin . '" value="1">
	<input type="hidden" name="tag_value" value="' . _U_AM_TAG . '">
	<input type="hidden" name="tag-value" value="' . _U_AM_TAG . '">
	<input type="hidden" name="dev-tag-value" value="' . _U_AM_TOKEN . '">
	<input type="submit" name="submit.add-to-cart" value="' . _U_AM_BUY_AMAZON . '">
	</form>
						</td>
					</tr>
				</tbody>
			</table>';
        return $am_tb . '<p style="font-size : 12px;text-align: center;"><a href="javaScript:history.go( -1 )">' . _U_AM_GOBACK . '</a></p>';
    }

    public function am_pages($page, $keyword, $mode, $am_sort)
    {
        global $_SERVER;
        $pages = 'Page:';
        for ($i = 1; $i <= $page; $i++) {
            if ($i > 10) {
                $pages .= '&nbsp;...More MAX: ' . $page . ' Pages';
                break;
            }
            $pages .= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?mode=am&amp;p=' . $i . '&amp;keyword=' . rawurlencode($keyword) . '&amp;media=' . $mode . '&amp;sort=' . $am_sort . '">' . $i . '</a>&nbsp;';
        }
        return $pages;
    }

    public function am_what_new($filename)
    {
        if (filemtime($filename) > time() - $this->ftime * 3600) {
            return true;
        }
        return false;
    }

    public function am_img_check($image)
    {
        $size = @getimagesize($image);
        if ($size) {
            return $size;
        }
        return false;
    }

    public function am_get_media($mode)
    {
        switch ($mode) {
            case 'books-jp':
                return _U_AM_BOOKS;
            case 'music-jp':
                return _U_AM_MUSIC;
            case 'classical-jp':
                return _U_AM_CLASSICAL;
            case 'dvd-jp':
                return _U_AM_DVD;
            case 'video-jp':
                return _U_AM_VIDEO;
            case 'electronics-jp':
                return _U_AM_ELECTRONICS;
            case 'software-jp':
                return _U_AM_SOFTWARE;
            case 'videogames-jp':
                return _U_AM_VIDEOGAMES;
            case 'kitchen-jp':
                return _U_AM_KITCHEN;
        }
    }

    public function am_get_reviewimage($rating)
    {
        switch ($rating) {
            case 5:
                return '<img src="./images/rank_stars5_5.gif" width="72" height="16" alt="Rating5" style="vertical-align : middle;margin:5px;border-style:none;">';
            case 4:
                return '<img src="./images/rank_stars5_4.gif" width="72" height="16" alt="Rating4" style="vertical-align : middle;margin:5px;border-style:none;">';
            case 3:
                return '<img src="./images/rank_stars5_3.gif" width="72" height="16" alt="Rating3" style="vertical-align : middle;margin:5px;border-style:none;">';
            case 2:
                return '<img src="./images/rank_stars5_2.gif" width="72" height="16" alt="Rating2" style="vertical-align : middle;margin:5px;border-style:none;">';
            case 1:
                return '<img src="./images/rank_stars5_1.gif" width="72" height="16" alt="Rating1" style="vertical-align : middle;margin:5px;border-style:none;">';
        }
    }
}


