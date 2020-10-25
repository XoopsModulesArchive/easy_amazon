<?php

if (!defined('XOOPS_MAINFILE_INCLUDED') || !defined('XOOPS_ROOT_PATH') || !defined('XOOPS_URL')) {
    trigger_error('Bad!!Access error none mainfile:');

    exit();
}

/*
nusoap.php ８３行目あたりを以下のように変更

+	var $soap_defencoding = 'UTF-8';
+	//var $soap_defencoding = 'ISO-8859-1';

*/

class AmazonSearch
{
    public $Client = null;

    public $Proxy = null;

    public $Details = [];

    public $BrowseId = [];

    public $SimilarProducts = [];

    public $Lists = [];

    public function __construct()
    {
        require_once 'new-nusoap.php';

        $this->Client = new soapclient(WSDL_FILE, true);

        if ($this->Client->getError()) {
            die($this->Client->getError());
        }

        $this->Proxy = $this->Client->getproxy();

        $this->Proxy->decodeUTF8(false);
    }

    public function asinsearch($asin, $mode = 'books-jp', $type = 'heavy')
    {
        $params = [
        'asin' => $asin,
'mode' => $mode,
'tag' => _U_AM_TAG,
'type' => $type,
'offer' => 'All',
'devtag' => _U_AM_TOKEN,
'locale' => AMAZON_LOCALE,
         ];

        $this->set_details($this->decode2internal($this->Proxy->asinsearchrequest($params)));
    }

    public function similaritysearch($asin, $type = 'heavy')
    {
        $params = [
        'asin' => $asin,
'tag' => _U_AM_TAG,
'type' => $type,
'devtag' => _U_AM_TOKEN,
'locale' => AMAZON_LOCALE,
         ];

        $this->set_details($this->decode2internal($this->Proxy->similaritysearchrequest($params)));
    }

    public function do_search($func, $req, $keyword = '', $mode = 'books-jp', $type = 'heavy', $page = 1, $am_sort = 'salesrank')
    {
        switch ($func) {
        case 'artist':
            $exec_func = 'artistsearchrequest';
            break;
        case 'author':
            $exec_func = 'authorsearchrequest';
            break;
        case 'manufacturer':
            $exec_func = 'manufacturersearchrequest';
            break;
        case 'director':
            $exec_func = 'directorsearchrequest';
            break;
        case 'browse_node':
            $exec_func = 'browsenodesearchrequest';
            break;
        }

        $req = $this->encode2utf($req);

        $keyword = $this->encode2utf($keyword);

        $params = [
        $func => $req,
'page' => $page,
'mode' => $mode,
'tag' => _U_AM_TAG,
'type' => $type,
'sort' => $am_sort,
'keywords' => $keyword,
'devtag' => _U_AM_TOKEN,
'locale' => AMAZON_LOCALE,
         ];

        if ('' == $keyword) {
            unset($params['keywords']);
        }

        $this->set_details($this->decode2internal($this->Proxy->$exec_func($params)));
    }

    public function keywordsearch($keyword, $mode = 'books-jp', $type = 'heavy', $page = 1, $am_sort = 'salesrank')
    {
        $keyword = $this->encode2utf($keyword);

        $params = [
        'keyword' => $keyword,
'page' => $page,
'mode' => $mode,
'tag' => _U_AM_TAG,
'type' => $type,
'sort' => $am_sort,
'devtag' => _U_AM_TOKEN,
'locale' => AMAZON_LOCALE,
         ];

        $this->set_details($this->decode2internal($this->Proxy->keywordsearchrequest($params)));
    }

    public function encode2utf($str)
    {
        $str = mb_convert_kana($str, 'asKV');

        return htmlentities(mb_convert_encoding($str, 'utf-8', 'auto'), 1, 'utf-8');
    }

    public function decode2internal(&$result)
    {
        ini_set('mbstring.internal_encoding', 'EUC-JP');

        $res = mb_convert_variables(mb_internal_encoding(), 'utf-8', $result);

        if ($res) {
            return $result;
        }

        return false;
    }

    //内容

    public function Get_Details()
    {
        return $this->Details;
    }

    //カテゴリー検索ＩＤ

    public function Get_BrowseId()
    {
        return $this->BrowseId;
    }

    //類似本Asin

    public function Get_SimilarProducts()
    {
        return $this->SimilarProducts;
    }

    public function Get_Lists()
    {
        return $this->Lists;
    }

    public function img_callback($image, $alt)
    {
        if (IMAGE_SIZE == 0) {
            $size = @getimagesize($image);

            if ($size) {
                return '<img src="' . $image . '" ' . $size[3] . ' alt="' . $alt . '" style="vertical-align : middle;float : right;">';
            }
        }

        return '<img src="' . $image . '" alt="' . $alt . '" style="vertical-align : middle;float : right;">';
    }

    public function set_details($result)
    {
        $this->Details['TotalResults'] = '';

        $this->Details['TotalPages'] = '';

        $this->detail_count = '';

        $this->Details = [];

        $this->Lists = [];

        $this->BrowseId = [];

        $this->Details['Authors'] = [];

        if (!isset($result['faultstring']) && is_array($result['Details'])) {
            $this->Details['TotalResults'] = $result['TotalResults'] ?? '';

            $this->Details['TotalPages'] = $result['TotalPages'] ?? '';

            $i = 0;

            foreach ($result['Details'] as $index => $obj) {
                $this->Details['ProductDescription'] = $obj['ProductDescription'] ?? '';

                $this->Details['Url'][$i] = $obj['Url'] ?? '';

                $this->Details['Asin'][$i] = $obj['Asin'] ?? '';

                $this->Details['ProductName'][$i] = $obj['ProductName'] ?? '';

                $this->Details['Catalog'][$i] = $obj['Catalog'] ?? '';

                $this->Details['Manufacturer'][$i] = $obj['Manufacturer'] ?? ''; //出版社

                $this->Details['ImageUrlSmall'][$i] = $this->img_callback($obj['ImageUrlSmall'], $obj['ProductName']);

                $this->Details['ImageUrlMedium'][$i] = $this->img_callback($obj['ImageUrlMedium'], $obj['ProductName']);

                $this->Details['ImageUrlLarge'][$i] = $this->img_callback($obj['ImageUrlLarge'], $obj['ProductName']);

                $this->Details['samllImage'][$i] = $obj['ImageUrlSmall'] ?? '';

                $this->Details['medImage'][$i] = $obj['ImageUrlMedium'] ?? '';

                $this->Details['openImage'][$i] = $obj['ImageUrlLarge'] ?? '';

                $this->Details['ListPrice'][$i] = $obj['ListPrice'] ?? '';

                $this->Details['OurPrice'][$i] = $obj['OurPrice'] ?? ''; //価格

                $this->Details['UsedPrice'][$i] = $obj['UsedPrice'] ?? '';

                $this->Details['SalesRank'][$i] = $obj['SalesRank'] ?? '';

                $this->Details['Media'][$i] = $obj['Media'] ?? '';

                $this->Details['Isbn'][$i] = $obj['Isbn'] ?? '';

                $this->Details['Availability'][$i] = ($obj['Availability']) ?: '';

                if (isset($obj['Authors'])) {
                    $this->Details['Authors'][$i] = implode('&nbsp;', $obj['Authors']); //著者
                }

                if (isset($obj['Lists'])) {
                    foreach ($obj['Lists'] as $lis => $Lists) {
                        $this->Lists[$i][$lis] = $Lists;
                    }
                }

                if (isset($obj['BrowseList'])) {
                    foreach ($obj['BrowseList'] as $key => $BrowseList) {
                        $this->BrowseId[$i][$BrowseList['BrowseId']] = $BrowseList['BrowseName'];
                    }
                }

                if (isset($obj['Reviews'])) {
                    $this->Details['AvgCustomerRating'][$i] = $obj['Reviews']['AvgCustomerRating'];

                    $this->Details['TotalCustomerReviews'][$i] = $obj['Reviews']['TotalCustomerReviews'];

                    $this->Details['AvgCustomerRating'][$i] = $obj['Reviews']['AvgCustomerRating'];

                    if (isset($obj['Reviews']['CustomerReviews'])) {
                        foreach ($obj['Reviews']['CustomerReviews'] as $cus => $CustomerReviews) {
                            $this->Details['Rating_' . $i][$cus] = $CustomerReviews['Rating'];

                            $this->Details['Summary_' . $i][$cus] = $CustomerReviews['Summary'];

                            $this->Details['Comment_' . $i][$cus] = $CustomerReviews['Comment'];
                        }
                    }
                }

                if (isset($obj['SimilarProducts'])) {
                    foreach ($obj['SimilarProducts'] as $sim => $SimilarProducts) {
                        $this->SimilarProducts[$i] = $SimilarProducts;
                    }
                }

                $i++;

                if (_U_AM_PAGE_ON_DETAILS == $i) {
                    break;
                }
            }

            $this->detail_count = $i;
        } else {
            $this->Details['fault'] = $result['detail'];
        }
    }
}
