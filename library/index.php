<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------

// Parameters
if ($_GET) {
  $index_parameter = $_GET['i'];
} else {
  $index_parameter = "stack";
}
// ---------------------------------------------------- PAGE SPECIFIC SETUP

// LOAD METADATA ----------------------------------------------------------

  // Get the library metadata
  $ld_json = file_get_contents("../_depo/metadata.json");
  $ld = json_decode($ld_json,true);

  // Get Dates Finished Reading Index
  /* TO BE REWORKED
    $di_json = file_get_contents("../_depo/index.dates.json");
    $di = json_decode($di_json,true);
    $disp_index = $di['finis']['by_date']['y-m'];
    include('../_bits/library_shelves.php');
  */
  if ($index_parameter == "date") {
    // Get Dates Added Index
    $di_json = file_get_contents("../_depo/index.dates.json");
    $di = json_decode($di_json,true);
    $disp_index = $di['added']['by_date']['y-m'];

  } else if ($index_parameter == "author") {
    // Get Authors Index
    $ai_json = file_get_contents("../_depo/index.authors.json");
    $disp_index = $ai = json_decode($ai_json,true);

  } else if ($index_parameter == "publisher") {
    // Get Publishers Index
    $pi_json = file_get_contents("../_depo/index.publishers.json");
    $disp_index = $pi = json_decode($pi_json,true);

  } else if ($index_parameter == "bundle") {
    // Get Bundle Index
    $bi_json = file_get_contents("../_depo/index.bundles.json");
    $disp_index = $bi = json_decode($bi_json,true);

  } else if ($index_parameter == "stack") {
    // Get Stacks Index
    $si_json = file_get_contents("../_depo/index.stacks.json");
    $disp_index = $si = json_decode($si_json,true);

  } else if ($index_parameter == "like") {
    // Get Liked State Index
    $li_json = file_get_contents("../_depo/index.liked.json");
    $disp_index = $li = json_decode($li_json,true);

  } else if ($index_parameter == "color") {
    // Get Color Index
    $ci_json = file_get_contents("../_depo/index.colors.json");
    $ci = json_decode($ci_json,true);
    $disp_index = $ci['primary'];

  } else if ($index_parameter == "series") {
    // Get Series Index
    $ei_json = file_get_contents("../_depo/index.series.json");
    $disp_index = $ei = json_decode($ei_json,true);

  } else if ($index_parameter == "reads") {
    // Get Read State Index
    $ri_json = file_get_contents("../_depo/index.reads.json");
    $disp_index = $ri = json_decode($ri_json,true);
  }
// ---------------------------------------------------- LOAD METADATA


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Library</title>

  <?php include_once ('../_bits/favicon.php'); ?>
  <?php include_once ('../_bits/bs_js_css.php'); ?>

  <!-- Our stuff -->
  <link rel="stylesheet" rev="stylesheet" href="/_site/css/specific/library.css" media="screen" />

  <!-- MASONRY Must come after our CSS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
  <!-- JQCloud -->
  <script src="/_site/vendors/jqcloud/jqcloud.min.js"></script>
  <link rel="stylesheet" href="/_site/vendors/jqcloud/jqcloud.min.css">

</head>
<body id="library" class="top <?=$index_parameter; ?>">

<?php include_once ('../_bits/navbar.php'); ?>

<div class="container">
<div class="view-menu">
  <ul id="nav-index">
    <li id="nav-stack" class=""><a href="/library/?i=stack">Stacks</a></li>
    <li id="nav-reads" class=""><a href="/library/?i=reads">By Read State</a></li>
    <li id="nav-date" class=""><a href="/library/?i=date">By Date Added</a></li>
    <?php /*
    <li><a href="/library/?i=author">By Author</a></li>
    <li><a href="/library/?i=publisher">By Publisher</a></li>
    <li><a href="/library/?i=series">By Series</a></li>
    <li><a href="/library/?i=bundle">By Bundle</a></li>
    <li><a href="/library/?i=like">Likes</a></li>
    <li><a href="/library/?i=color">Colors</a></li>
    */?>
  </ul>
</div>
<?php
  // "Library Data" == Tagged Books
  $ld = $ld;
  //echo "<pre>";print_r($ld); echo "</pre>";
  // Print "Shelves"
  include('../_bits/library_shelves.php');
?>


<?php
// Include the Tags cloud generator.
  // Get the Tag Indexes
  $ti_json = file_get_contents(_WR."/_depo/index.tags-tags.json");
  $ti = json_decode($ti_json,true);
  $ti = $ti['tag'];

  $label_index = $ti;
  $label_uri_base = '/library/tags/';
  $label_uri_type = "label";
  $label_cloud_div_id = "tagcloud";
  $label_cloud_header = "<h1>Tags</h1>";
  include _WR.'/_bits/_labels_cloud.php';
?>


</div>

<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>