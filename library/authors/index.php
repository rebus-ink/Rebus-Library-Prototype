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
// Get the Author Indexes
$aia_json = file_get_contents(_WR."/_depo/index.auth-author.json");
$aia = json_decode($aia_json,true);
$aib_json = file_get_contents(_WR."/_depo/index.auth-book.json");
$aib = json_decode($aib_json,true);
// Get Read State Index
$ri_json = file_get_contents(_WR."/_depo/index.reads.json");
$ri = json_decode($ri_json,true);

// Is there an Author?
if (isset($_GET['a'])) {
  // Get the Query String
  $auid = $_GET['a'];
  $aib = $aib['book'];
  // Do we have any books by this author?
  $abid = recursive_array_search($auid,$aib);
  // If we do, set some vars
  if ($abid) {
    $babids = $aia['author'][$auid]['books'];
    $baban  = $aia['author'][$auid]['name'];
    $babco  = $aia['author'][$auid]['count'];
  }
} else {
  $auid = NULL; $abid = NULL;
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>L / A / <?= $baban; ?></title>

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
if ($abid) {

  // Context View Title & Count
  if ($babco > 1) {
    $b = "books";
  } else {
    $b ="book";
  }

  echo "<h1><div class=\"index-count\">".$babco." ".$b."</div>".$baban."</h1>";


  // Library Data only of Authored Books
  foreach ($babids as $k => $bid) {
    $abld[$bid] = $ld[$bid];
  }
  // "Library Data" == Authored Books
  $ld = $abld;

  //echo "<pre>"; print_r($ri); echo "</pre>";
  // "Read State" == Read State of books with this author.
  foreach ($ri as $rs => $rbids) {
    if (isset($ri[$rs])) {
      $rais = array_intersect($ri[$rs],$babids);
      // if the Read State array is not empty, add it to the new Read State Index
      if (count($rais) > 0) {
        $ri2[$rs] = $rais;
      }
    }
  }
  $disp_index = $ri2;
  //echo "<pre>"; print_r($ri); echo "</pre>";
  // Print "Shelves"
  if(count($ri) < 6) {
  $shelf_display = "vertical"; }
  require _WR.'/_bits/library_shelves.php';

  echo "<h1>All Authors</h1>";

} else {
  if ($auid && !$abid) {
    echo "<h1>We don't seem to have any books by this author.</h1><h2>Perhaps you might like to pick one of these?</h2><br />";
  } elseif (!$auid && !$abid) {
    echo "<h1>Authors</h1>";
  }
}
?>
<?php //include _WR.'/_bits/author_cloud.php'; ?>
</div>

<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>