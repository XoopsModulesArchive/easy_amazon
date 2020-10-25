<?php

// $Id: blocks.php,v 1.1 2006/03/27 15:45:15 mikhail Exp $
// Blocks
define('_MB_UAM_PICKUP', '書籍：DVD：音楽－検索');
define('_MB_UAM_KEYWORD', 'キーワードで探す');
define('_MB_UAM_BESTSELLER', 'ベストセラー');
define('_MB_UAM_SEARCH', '検　索');
define('_MB_UAM_PMRANK', '注目商品');
define('_MB_UAM_REVIEWRANK', 'レビュー評価');
define('_MB_UAM_PRICERANK', '価格(安いものから)');
define('_MB_UAM_INVERSEPRICERANK', '価格(高いものから)');
define('_MB_UAM_DATERANK', '発売日');
define('_MB_UAM_S_MEDIA', 'メディア別で探す');
define('_MB_UAM_BOOKS', '書籍');
define('_MB_UAM_MUSIC', 'ポピュラー音楽');
define('_MB_UAM_CLASSICAL', 'クラシック音楽');
define('_MB_UAM_DVD', 'DVD');
define('_MB_UAM_VIDEO', 'ビデオ');
define('_MB_UAM_ELECTRONICS', '家電');
define('_MB_UAM_SOFTWARE', 'ソフトウェア');
define('_MB_UAM_VIDEOGAMES', 'TVゲーム');
define('_MB_UAM_KITCHEN', 'ホーム&amp;キッチン');
define('_MB_UAM_AUTHOR', '著者名（書籍）');
define('_MB_UAM_S_AUTHOR', '著者で探す');
define('_MB_UAM_ARTIST', 'アーティスト名（音楽）');
define('_MB_UAM_S_ARTIST', 'アーティストで探す');
define('_MB_UAM_DIRECTOR', '監督名（ビデオ,DVD）');
define('_MB_UAM_S_DIRECTOR', '監督名で探す');
define('_MB_UAM_SELECTOR', '
	書籍（和書）<br>
	<select name="evt_type" onChange="u_search_evn(this,\'books\')">
	<option value="--" selected>書籍（和書）－選択</option>
	<option value="466300">新刊</option>
	<option value="746102">楽譜</option>
	<option value="466284">文学</option>
	<option value="571582">哲学と宗教</option>
	<option value="571584">社会と政治</option>
	<option value="492152">ノンフィクション</option>
	<option value="466286">旅行と地理</option>
	<option value="466282">ビジネスと経歴</option>
	<option value="492054">投資と財政管理</option>
	<option value="466290">科学</option>
	<option value="466298">コンピュータ</option>
	<option value="466294">芸術と写真</option>
	<option value="466296">娯楽</option>
	<option value="466292">スポーツと趣味</option>
	<option value="466304">家と家族</option>
	<option value="466302">外国語リファレンス</option>
	<option value="3148931">教育</option>
	<option value="466306">子供の本</option>
	<option value="466280">漫画とアニメ</option>
	</select><br>音楽<br>
	<select name="evt_type" onChange="u_search_evn(this,\'music\')">
	<option value="--" selected>音楽－選択</option>
	<option value="569170">J-ポップ</option>
	<option value="569290">ポピュラー</option>
	<option value="569292">ロック</option>
	<option value="569298">ハードロック</option>
	<option value="562050">ブルース&amp;カントリー</option>
	<option value="569318">ソウル R&amp;B</option>
	<option value="569320">ヒップポップ</option>
	<option value="569322">ダンス</option>
	<option value="562052">ジャズ</option>
	<option value="562058">サウンドトラック</option>
	<option value="569174">バラード</option>
	<option value="562060">アニメ</option>
	<option value="899296">スポーツ</option>
	<option value="569186">伝統音楽</option>
	<option value="562062">子供向け</option>
	<option value="562064">新世代</option>
	<option value="562056">世界</option>
	<option value="701040">クラッシック</option>
	</select><br>DVD<br>
	<select name="evt_type" onChange="u_search_evn(this,\'dvd\')">
	<option value="--" selected>DVD－選択</option>
	<option value="562014">邦画</option>
	<option value="562016">洋画</option>
	<option value="562018">音楽</option>
	<option value="562020">アニメ</option>
	<option value="562022">趣味とフィットネス</option>
	<option value="562024">スポーツ</option>
	<option value="562028">TV</option>
	<option value="564522">BOX</option>
	<option value="896246">adult</option>
	</select><br>ビデオ<br>
	<select name="evt_type" onChange="u_search_evn(this,\'video\')">
	<option value="--" selected>ビデオ－選択</option>
	<option value="561984">邦画</option>
	<option value="561986">洋画</option>
	<option value="561988">音楽</option>
	<option value="561990">アニメ</option>
	<option value="561992">趣味とフィットネス</option>
	<option value="561994">スポーツ</option>
	<option value="561996">家庭</option>
	<option value="561998">TV</option>
	<option value="564546">輸入</option>
	</select><br>ゲーム<br>
	<select name="evt_type" onChange="u_search_evn(this,\'videogames\')">
	<option value="--" selected>ゲーム－選択</option>
	<option value="637874">Play Station 2</option>
	<option value="637876">Play Station</option>
	<option value="637878">Gamecube</option>
	<option value="637880">Game Boy Advance</option>
	<option value="637882">Game Boy</option>
	<option value="639096">Xbox</option>
	<option value="637886">その他</option>
	</select><br>ソフトウェア<br>
	<select name="evt_type" onChange="u_search_evn(this,\'software\')">
	<option value="--" selected>ソフト－選択</option>
	<option value="689132">ゲーム</option>
	<option value="1040140">子供向け</option>
	<option value="637656">趣味</option>
	<option value="637658">外国語</option>
	<option value="637648">インターネット</option>
	<option value="637644">ビジネス</option>
	<option value="1040106">業種別ビジネスソフト</option>
	<option value="637652">グラフィック</option>
	<option value="637654">音楽</option>
	<option value="637662">ユーティリティ</option>
	<option value="1040116">ネットワーク管理</option>
	<option value="637650">プログラミング</option>
	<option value="637666">OS</option>
	<option value="3137861">マック</option>
	</select><br>家電<br>
	<select name="evt_type" onChange="u_search_evn(this,\'electronics\')">
	<option value="--" selected>家電－選択</option>
	<option value="3371371">デジカメ</option>
	<option value="3371441">DVDプレイヤー</option>
	<option value="3371411">オーディオ</option>
	<option value="3371341">パソコン</option>
	<option value="3371351">PC周辺機器</option>
	<option value="3371361">プリンター・スキャナ</option>
	<option value="3371381">ネットワーク設備</option>
	<option value="3371401">PDA</option>
	<option value="3371421">アクセサリー</option>
	<option value="3371391">メモリー、記録媒体</option>
	<option value="3371431">オーディオビジュアル</option>
	<option value="3371461">TVゲーム</option>
	</select>
');
