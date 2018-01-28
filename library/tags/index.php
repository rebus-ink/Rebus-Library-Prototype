<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------
$index_parameter = "reads";
// ---------------------------------------------------- PAGE SPECIFIC SETUP

// Get the library metadata
$ld_json = file_get_contents(_WR."/_depo/metadata.json");
$ld = json_decode($ld_json,true);
// Get the Tag Indexes
$ti_json = file_get_contents(_WR."/_depo/index.tags-tags.json");
$ti = json_decode($ti_json,true);
// Get Read State Index
$ri_json = file_get_contents(_WR."/_depo/index.reads.json");
$ri = json_decode($ri_json,true);

// Is there a Tag?
if (isset($_GET['t'])) {
  // Get the Query String
  $tag = urlencode($_GET['t']); $dtag = urldecode($tag);
  $ti = $ti['tag'];
  // Do we have any books with this tag?
  $tid = recursive_array_search($tag,$ti);
} else {
  $tag = NULL; $dtag = NULL; $tid = NULL;
}


?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>L / T / <?= $dtag; ?></title>

  <?php include _WR.'/_bits/favicon.php'; ?>
  <?php include _WR.'/_bits/bs_js_css.php'; ?>

  <!-- Our stuff -->
  <link rel="stylesheet" rev="stylesheet" href="/_site/css/specific/library.css" media="screen" />

  <!-- MASONRY Must come after our CSS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
  <!-- JQCloud -->
  <script src="/_site/vendors/jqcloud/jqcloud.min.js"></script>
  <link rel="stylesheet" href="/_site/vendors/jqcloud/jqcloud.min.css">
</head>
<body id="library">

<?php include _WR.'/_bits/navbar.php'; ?>

<div class="container">

<?php
if ($tid) {

  $bids = $ti[$tid]['books'];

  // Context View Title & Count
  $count_books_w_tag = $ti[$tid]['count'];
  if ($count_books_w_tag > 1) {
    $b = "books";
  } else {
    $b ="book";
  }

  echo "<h1><div class=\"index-count\">".$count_books_w_tag." ".$b."</div>".$dtag."</h1>";


  // Library Data only of Tagged Books
  foreach ($bids as $bid => $bcaid) {
    $tbld[$bid] = $ld[$bid];
  }
  // "Library Data" == Tagged Books
  $ld = $tbld;

  //echo "<pre>BIDS: "; print_r($bids); echo "</pre>";
  //echo "<pre>RI "; print_r($ri); echo "</pre>";
  // "Read State" == Read State of books with this tag
  foreach ($ri as $rs => $rbids) {
    if (isset($ri[$rs])) {
      $rais = array_intersect_key($ri[$rs],$bids);
      // if the Read State array is not empty, add it to the new Read State Index
      if (count($rais) > 0) {
        $ri2[$rs] = $rais;
      }
    }
  }
  $disp_index = $ri2;
  // Print "Shelves"
  if(count($ri) < 6) {
  $shelf_display = "vertical"; }
  include _WR.'/_bits/library_shelves.php';

  $label_cloud_header = "<h1>All Tags</h1>";

} else {
  if ($tag && !$tid) {
    $label_cloud_header = "<h1>No book matches this tag.</h1><h2>Perhaps you might like to pick one of these?</h2><br />";
  } elseif (!$tag && !$tid) {
    $label_cloud_header = "<h1>Tags</h1>";
  }
}
?>
<?php //include _WR.'/_bits/tag_cloud.php'; ?>



<?php
// Include the Tags cloud generator.
  $label_index = $ti;
  $label_uri_base = '/library/tags/';
  $label_uri_type = "label";
  $label_cloud_div_id = "tagcloud";
  $label_cloud_header = $label_cloud_header;
  include _WR.'/_bits/_labels_cloud.php';
?>

</div>

<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>