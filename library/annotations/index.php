<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------
// ---------------------------------------------------- PAGE SPECIFIC SETUP
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=380" />
	<title>Annotation</title>
  <link rel="stylesheet" rev="stylesheet" href="/_site/css/insert.css" media="screen" />
  <script src="/_site/js/jquery-2.1.1.min.js"></script>
  <script src="/_site/js/masonry.pkgd.js"></script>
</head>
<body id="annotations" class="individual">
<?php

$ld_json = file_get_contents(_WR."/_depo/metadata.json");
$ld = json_decode($ld_json,true);

?>





</body>
</html>