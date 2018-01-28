<?php
// SETUP ------------------------------------------------------------------
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------
// ---------------------------------------------------- PAGE SPECIFIC SETUP
?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
$lib_data_json = file_get_contents(_WR."/_depo/metadata.json");
$lib_data = json_decode($lib_data_json,true);
$anno_data_json = file_get_contents(_WR."/_depo/index_pp.anno-type.json");
$anno_data = json_decode($anno_data_json,true);
$anno_data = $anno_data['type']['highlights']['time'];

// Random Quote of the moment --------------------------------------------------
$rand_anno_id = array_rand($anno_data,1);
$rand_book_id = $anno_data[$rand_anno_id];

$bk_caid = $rand_book_id;
$bk = $lib_data[$bk_caid];
$bv_json = file_get_contents(_WR . "/_depo/" . $bk['uuid'] . "/book.json");
$bv = json_decode($bv_json,true);
//$av['annotation'] = $bv['annotations'][$rand_anno_id];
$av['quote']['text'] = $bv['annotations'][$rand_anno_id]['text_j'][0];
$av['book']['uuid'] = $bk['uuid'];
$av['book']['title']            = $bv['title'];
$av['book']['author']           = $bv['authors_pp'];
$av['book']['uuid']             = $bv['uuid'];
$av['book']['pub_date_ts']      = $bv['pub_date_ts'];
$av['book']['uri']              = "/library/book/".$bv['uuid'];
if ($bv['has_cover'] == 1) {
  $av['book']['cover_path']     = "/_depo/".$bv['uuid']."/cover_128x128.jpg";
}
$av['book']['pages']            = $bv['pages'];
$av['book']['colors']           = $bv['colors'];

//echo "<pre>";print_r($av); echo "</pre>";
$av_json = json_encode($av);
echo $av_json;
//echo $bv_json;

?>