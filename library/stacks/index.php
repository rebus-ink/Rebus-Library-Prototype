<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------

// ---------------------------------------------------- PAGE SPECIFIC SETUP

// Get the library metadata
//$ld_json = file_get_contents(_WR."/_depo/metadata.json");
//$ld = json_decode($ld_json,true);
// Get the Tag Indexes
$si_json = file_get_contents(_WR."/_depo/index.stacks-stacks.json");
$si = json_decode($si_json,true);

// Is there a Tag?
if (isset($_GET['s'])) {
  // Get the Query String
  $stack = urlencode($_GET['s']);$dstack = urldecode($stack);

  // Do we have any stacks with this tag?
  //$sid = recursive_array_search($stack,$si);
  if (isset($si[$stack])) {
    $sid = $stack;
  } else {
    $sid = false;
  }
  //echo "<pre>"; var_dump($sid); echo "</pre>";
} else {
  $stack = NULL; $dstack = NULL; $sid = NULL;
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>L / T / <?= $si[$stack]['label']; ?></title>

  <?php include _WR.'/_bits/favicon.php'; ?>
  <?php include _WR.'/_bits/bs_js_css.php'; ?>

  <!-- Our stuff -->
  <link href="/_site/css/specific/book.css" media="screen" rel="stylesheet" type='text/css'>

  <!-- JQCloud -->
  <script src="/_site/vendors/jqcloud/jqcloud.min.js"></script>
  <link rel="stylesheet" href="/_site/vendors/jqcloud/jqcloud.min.css">
</head>
<body id="stack">

<?php include _WR.'/_bits/navbar.php'; ?>

<div class="container">

<?php
if ($sid) {

  // Context View Title & Count ---------------------------
  $count_books_w_tag = $si[$sid]['count'];
  if ($count_books_w_tag > 1) {
    $b = "books"; }
  else {
    $b ="book"; }
  echo "<h1><div class=\"index-count\">".$count_books_w_tag." ".$b."</div>“".$si[$stack]['label']."” Stack </h1>";
  // END Context View Title & Count -----------------------

  // Print out the books ----------------------------------
  $bids = $si[$sid]['books'];

  foreach($bids as $book_id => $book_cal_uuid) {
   $bookpath = _WR."/_depo/".$book_cal_uuid."/book.json";
   if (is_file($bookpath)) {
    // Now we can load the book's JSON data
    $bda_json[$book_id] = file_get_contents($bookpath);
    $bda[$book_id] = json_decode($bda_json[$book_id],true);
  } else {
    $bda[$book_id] = FALSE;
  }
$bd = $bda[$book_id];
?>

<div class="container book" id="<?= $bd['uuid'];?>">

  <div class="row" id="info-card"><!-- Start Row 1 -->

    <div class="col-sm-3">

      <div class="cover">
        <a href="/library/book/<?= $bd['uuid']; ?>" title="<?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>"><img class="img-responsive" src="/_depo/<?= $bd['uuid']; ?>/cover_320.jpg" alt="<?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>" border="0" /></a>
      </div>
      <div class="read">
        Read:
        <a href="/_depo/<?= $bd['uuid']; ?>/html/" title="Read HTML: <?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>">HTML</a>
        —
        <a href="read.php?bid=<?= $bd['uuid']; ?>" title="Read EPUB: <?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>">EPUB</a>
      </div>
    </div>

    <div class="col-sm-9">
      <div class="row book-header">
        <div class="col-sm-12">
          <?php if (isset($bd['title'])) { ?>
          <h2 class="title">
            <a href="/library/book/<?= $bd['uuid']; ?>" title="<?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>"><?= $bd['title']; ?></a>
          </h2>
          <?php } ?>
          <?php /* if (isset($bd['subtitle'])) { ?>
          <h3 class="subtitle">
            <?= $bd['subtitle']; ?>
          </h3>
          <?php } */ ?>
          <?php if (isset($bd['authors_pp'])) { ?>
          <h4 class="author">
            <?php
            $authors = $bd['authors'];
            $how_many_authors = count($authors);
            $i = 0;
            $comma = ", ";
            foreach ($authors as $key => $author) {
              if ($i >= $how_many_authors-1) { $comma = "";}
              echo '<a href="/library/authors/'.$author['auid'].'" title="'.$author['name'].'">'.$author['name'].'</a>'.$comma;
              $i++;
            }
            ?>
          </h4>
          <?php } ?>
        </div>
      </div>
      <div class="row book-about"><!-- Left Column -->
        <div class="col-md-5">

          <?php if (isset($bd['doi'])) { ?>
          <div class="doi">
            DOI: <a rel="nofollow" href="https://doi.org/<?= $bd['doi']; ?>" title="DOI">
              <?= $bd['doi']; ?>
            </a>
          </div>
          <?php } ?>

          <?php /* if (isset($bd['tags_assoc'])) { ?>
          <div class="tags">
            <?php
            $how_many_tags = count($bd['tags_assoc']);
            $i = 0;
            $comma = ", ";
            foreach ($bd['tags_assoc'] as $tagid => $tag) {
              if ($i >= $how_many_tags-1) { $comma = ".";}
              echo '<a href="/library/tags/'.urlencode($tag).'" title="'.$tag.'">'.$tag.'</a>'.$comma;
              $i++;
            }
            ?>
          </div>
          <?php } */ ?>
        </div><!-- End Left Column -->
        <div class="col-md-4"><!-- Right Column -->

          <?php if (isset($bd['pages'])) { ?>
          <div class="pages">
            ~<?= $bd['pages']; ?> pages
          </div>
          <?php } ?>

          <?php /* if (isset($bd['reads'])) { ?>
          <div class="readstate">
            <?= $bd['reads']; ?>
          </div>
          <?php } */ ?>
        </div><!-- End Right Column -->
      </div>
      <div class="row book-annotations">
        <div class="col-sm-12">



      <?php // ANNOTATIONS ---
      if (isset($bd['annotations'])) { ?>
          <div class="annotations">
            <h3 class="section-title">
              My Annotations
            </h3>
            <?php
            $count = 1;
            foreach ($bd['annotations'] as $ats => $ad) {
            ?>
            <div class="well annotation <?= $ad['iam']; ?>" id="<?= $ad['timestamp']; ?>">
              <div class="annotation-text" id="<?= $ad['location']; ?>">
                <?php
                if ($ad['iam'] == "bookmark") { ?>
                  Bookmark
                <?php
                } else {
                  /* We could also use text_h here, giving us <p></p> wrapped lines. */
                  // echo $ad['text_h'];
                  $an_lines = count($ad['text_j']);
                  foreach ($ad['text_j'] as $ak => $at ) {
                    echo $at;
                    if ($an_lines > ($ak + 1) ) {
                      echo "<br />";
                    } // if
                  } // foreach text_j
                } // if bookmark ?>
              </div>
              <div class="annotation-date">
                <?php
                if ($ad['iam'] == "highlight") { ?>
                <a href="/_depo/<?= $bd['uuid']; ?>/html/?anno=<?= strip_tags(substr($ad['text_h'],0,20)); ?>##<?= strip_tags(substr($ad['text_h'],0,20)); ?>">※</a>&nbsp;
                <?php
                } // if iam
                 ?>
                <?php
                if ($ad['iam'] != "bookmark") { ?>
                  <a href="#<?= $ad['timestamp']; ?>" title="annotation permalink"><?= date("g:i a, l, F jS, Y",$ad['timestamp']); ?></a>
                  <?php //echo $count; ?>
                <?php
                } else { ?>
                  <?= date("g:i a, l, F jS, Y",$ad['timestamp']); ?>
                <?php
                } // end if not bookmark ?>
              </div>
            </div>
        <?php
          $count++;
          } ?>
        </div>
    <?php } ?>
    </div><!-- /Annotations -->



        </div>
      </div>
    </div>
  </div><!-- End Row 1 -->
</div><!-- End Book -->
<br><br>


















<?php
  }

  //echo "<pre>"; print_r($bd); echo "</pre>";
  //echo "<pre>"; print_r($ld); echo "</pre>";


  // End Print out the books ------------------------------



  // Set the Label for the labelcloud display
  $display_labelcloud = FALSE;
  $label_cloud_header = "<h1><a href='/library/?i=stack'>Stacks</a></h1>";
} else {
  if ($stack && !$sid) {
    $label_cloud_header = "<h1><a href='/library/?i=stack'>Stacks</a></h1><h2>No book matches this tag.<br>Perhaps you might like to pick one of these?</h2><br />";
    $display_labelcloud = TRUE;
  } elseif (!$stack && !$sid) {
    $label_cloud_header = "<h1><a href='/library/?i=stack'>Stacks</a></h1>";
    $display_labelcloud = TRUE;
  }
}

// Include the Tags cloud generator ---------------------------------------------
  if ($display_labelcloud) {
    $label_index = $si;
    $label_uri_base = '/library/stacks/';
    $label_uri_type = "id";
    $label_cloud_div_id = "stackscloud";
    $label_cloud_header = $label_cloud_header;
    $label_cloud_height = "250px";
    include _WR.'/_bits/_labels_cloud.php';
  }
?>

</div>

<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>