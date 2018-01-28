<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------
// Get Markdown
//require _WR.'/_code/libs/PHP_Markdown_1.0.2/markdown.php';
// ---------------------------------------------------- PAGE SPECIFIC SETUP

// LOAD METADATA ----------------------------------------------------------
// ---------------------------------------------------- LOAD METADATA


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Library</title>

  <?php include_once (_WR . '/_bits/favicon.php'); ?>
  <?php include_once (_WR . '/_bits/bs_js_css.php'); ?>

  <!-- Our stuff -->
  <link rel="stylesheet" rev="stylesheet" href="/_site/css/specific/library.css" media="screen" />

</head>
<body id="about" class="about">

<?php include_once (_WR . '/_bits/navbar.php'); ?>

<div class="content container">

<h1>About this</h1>

<h2>Hello! — Welcome to the Rebus Library Prototype.</h2>

<br>
<img class="img-responsive" src="LibraryShelf.jpg">

<br><br>

<h3>This prototype begins to answer the questions</h3>
<ol>
<li><strong>“What experiences can we explore with a personal eBook library built entirely using simple, Open Web technologies?”</strong></li>
<li>“What if eBooks were WebBooks?”</li>
</ol>

<br><br>

<h3>Some nice things to notice</h3>
<ol>
  <li>In <a href="/library/">Library</a> views, book cover thumbnails are scaled according to page counts, giving us a sense of how "big" the book may be.</li>
  <li>Books can be “<a href="/library/stacks/6">stacked</a>.”</li>
  <li>Annotations are made available to us, and can be used any way we like. Whether by copy/paste, or with eventual* tools for piping into our favourite writing or sharing environments.</li>
    <ul>
      <li>Displayed as part of a book’s <a href="/library/book/ff3e5df6-7671-4d21-9508-77c9aee0223e">table-top information</a> overview.</li>
      <li>Deep-linked into the book, to be consulted in-situ</li>
      <li>Displayed alongside its book <a href="/library/stacks/6">in a stack</a>.</li>
      <li>As part of <a href="/">a screensaver</a>, piping annotations into our living spaces (<em>see photo above</em>).</li>
      <li>An <a href="/library/annotations/random.php">API which returns a random annotation in JSON format</a>.</li>
    </ul>
    <li><a href="/library/book/526865f3-d0d9-4ffa-a6f9-7d93a50600da#similar-book">Similar books</a> can be shown, based on subject proximity (and eventually, according to any heuristics a reader may find intersting or useful.)</li>
    <li>Citations can be auto-generated in any necessary format.</li>
</ol>
  <p><small>* Because it is open source and web-based, creating simple apps and plugins is possible for anyone with the know-how.</small></p>
</div>

</div>

<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>