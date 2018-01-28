<?php
// SETUP ------------------------------------------------------------------
//error_reporting(0);
//print_r($_SERVER);
require_once $_SERVER['DOCUMENT_ROOT'].'/_bits/sitestrap.php';
// ------------------------------------------------------------------ SETUP

// PAGE SPECIFIC SETUP ----------------------------------------------------
// Get the library metadata
$ld_json = file_get_contents(_WR."/_depo/metadata.json");
$ld = json_decode($ld_json,true);
// Get the Author Indexes
$aia_json = file_get_contents(_WR."/_depo/index.auth-author.json");
$aia = json_decode($aia_json,true);
$aia = $aia['author'];
// Get Markdown
require _WR.'/_code/libs/PHP_Markdown_1.0.2/markdown.php';
// Get Smarty
//require _WR.'/_code/libs/smarty-3.1.30/Smarty.class.php';
// ---------------------------------------------------- PAGE SPECIFIC SETUP

// Get the Query String
$book = $_GET['bk'];
  //print_r( $book );

$bookpath = _WR.'/_depo/' . $book . '/book.json';
if (is_file($bookpath)) {
  // Now we can load the book's JSON data
  $bd_json = file_get_contents($bookpath);
  $bd = json_decode($bd_json,true);
} else {
  $bd = FALSE;
}
//echo "<pre>";print_r($bd); echo "</pre>";
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>L / B / "<?= $bd['title']; ?>" - <?= $bd['authors_pp']; ?></title>

  <?php include _WR.'/_bits/favicon.php'; ?>
  <?php include _WR.'/_bits/bs_js_css.php'; ?>

  <!-- Our stuff -->
  <link href="/_site/css/specific/book.css" media="screen" rel="stylesheet" type='text/css'>

</head>
<body>

<?php include _WR.'/_bits/navbar.php'; ?>
<div class="container book" id="<?= $bd['uuid'];?>">

  <div class="row" id="info-card"><!-- Start Row 1 -->

    <div class="col-sm-3">

      <div class="cover">
        <a href="/_depo/<?= $bd['uuid']; ?>/html/" title="<?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>"><img class="img-responsive" src="/_depo/<?= $bd['uuid']; ?>/cover_320.jpg" alt="<?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>" border="0" /></a>
      </div>
      <div class="read">
        <a href="/_depo/<?= $bd['uuid']; ?>/html/" title="Read HTML: <?= $bd['title']; ?> by <?= $bd['authors_pp']; ?>">Read Webbook</a>
      </div>
    </div>

    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-12">
          <?php if (isset($bd['title'])) { ?>
          <h2 class="title">
            <?= $bd['title']; ?>
          </h2>
          <?php } ?>
          <?php if (isset($bd['subtitle'])) { ?>
          <h3 class="subtitle">
            <?= $bd['subtitle']; ?>
          </h3>
          <?php } ?>
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

      <div class="row" id="about-book">

        <div class="col-md-5">

          <div class="pubdate"><?php echo date("F, Y",$bd['pub_date_ts']); ?></div>

          <?php if (isset($bd['doi'])) { ?>
          <div class="doi">
            DOI: <a rel="nofollow" href="https://doi.org/<?= $bd['doi']; ?>" title="DOI">
              <?= $bd['doi']; ?>
            </a>
          </div>
          <?php } ?>

          <?php if (isset($bd['isbn'])) { ?>
          <div class="isbn">
            ISBN: <a rel="nofollow" href="http://www.worldcat.org/isbn/<?= $bd['isbn']; ?>" title="ISBN Reference">
              <?= $bd['isbn']; ?>
            </a>
          </div>
          <?php } ?>

        </div>

        <div class="col-md-4">

          <?php if (isset($bd['pages'])) { ?>
          <div class="pages">
            ~<?= $bd['pages']; ?> pages
          </div>
          <?php } ?>

          <?php if (isset($bd['reads'])) { ?>
          <div class="readstate">
            <?= $bd['reads']; ?>
          </div>
          <?php } ?>

          <?php if (isset($bd['provenance_cln'])) { ?>
          <div class="provenance">
            <h5>Provenance</h5>
            <?php
            $provenance_html = Markdown($bd['provenance_cln']);
            ?>
            <?= $provenance_html; ?>
          </div>
          <?php } ?>


          <div class="cite-book">
            <a class="" data-toggle="modal" data-target="#myModal">Cite this book</a>
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cite this book</h4>
                  </div>
                  <div class="modal-body">
                    <p>
<?php
require(_WR."/_code/libs/php-citation-builder/CitationBuilder.php");
use \CitationBuilder\CitationBuilder;
  $spec = "{@authors_pp. }{“<em>@title</em>”.}{ https://doi.org/@doi}";
$cb = new CitationBuilder($spec, $bd);
$citation = $cb->build();
echo $citation;
?>
<br><br>
                    <?= $bd['authors_so'][0]; ?>.
                    "<?php echo "<em>".$bd['title']; if (isset($bd['subtitle'])) { echo ": ".$bd['subtitle'].""; } echo "</em>"; ?>".
                    <?php if (isset($bd['publisher'])) { echo "".$bd['publisher']['name'].", "; } ?>
                    <?php if (isset($bd['pub_date_ts'])) { echo "(".date('Y',$bd['pub_date_ts']).")"; } ?>
                    :
                    <?php if (isset($bd['doi'])) { echo "https://doi.org/".$bd['doi'].""; } ?>
                    <?php //if (isset($bd['isbn'])) { echo "ISBN ".$bd['isbn'].""; } ?>
                    <!--Page
                    [<?= $bd['uri']; ?>]-->
                    </p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End "cite-book" -->

        </div>

      </div>
      <div class="row" id="tags-book">
        <div class="col-sm-12">
          <?php if (isset($bd['tags_assoc'])) { ?>
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
          <?php } ?>
        </div>
      </div>


      <?php
        // If we have any other books by any author of this book …
        //if (isset($bd['authors_more'])) {

      // Disabled the IF check here because "Similar Books" depends on a var set in here… :(
          include _WR . '/_bits/_books_same_authors.php';
        //}
      ?>

      <?php
        // If this book is part of a stack…
        if ($bd['stacks_assoc']) {
            include _WR . '/_bits/_books_stacks.php';
        }
      ?>

      <?php
        // If this book has tags, we can generate a list of some similar books
        if ($bd['tags_assoc']) {
            include _WR . '/_bits/_books_similar.php';
        }
      ?>

    </div>
  </div><!-- End Row 1 -->



  <div class="row" id="extra-content"><!-- Start Row 2 -->
    <div class="col-md-7 col-md-push-5">

      <?php // READING NOTES ---
      if (isset($bd['reading_notes_cln'])) { ?>
      <div class="reading_notes">
        <h3 class="section-title" id="reading_notes">
          Reading Notes
        </h3>
        <div class="blurb">
          <?php
          $reading_notes_html = Markdown($bd['reading_notes_cln']);
          ?>
          <?= $reading_notes_html; ?>
        </div>
      </div>
      <?php
      } // END READING NOTES ?>

      <?php // ANNOTATIONS ---
      if (isset($bd['annotations'])) { ?>
          <div class="annotations">
            <h3 class="section-title" id="annotations">
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

    <div class="col-md-5 col-md-pull-7">

      <?php // MY REVIEW ---
      if (isset($bd['my_review_cln'])) { ?>
      <div class="my_review">
        <h3 class="section-title" id="my_review">
          My review
        </h3>
        <div class="blurb">
          <?php
          $my_review_html = Markdown($bd['my_review_cln']);
          ?>
          <?= $my_review_html; ?>
        </div>
      </div>
      <?php
      } // END MY REVIEW ?>

      <?php
      // Let's include other people's quotes for this book, if we have some
      $book_quotes_file = _WR."/_depo/_pop_quotes/" . $book . ".json";
      if (is_file($book_quotes_file)) {

        $book_quotes_json = file_get_contents($book_quotes_file);
        $book_quotes_all = json_decode($book_quotes_json,true);
        //echo $book_quotes_file ."\n";
        //print_r($book_quotes_all);

        if ($book_quotes_all['quotes']) {
      ?>
      <div class="quotes">
        <h3 class="section-title" id="pop_quotes">
          Random Quote
        </h3>
      <pre><?php
        // Get the quotes
        $book_quotes = $book_quotes_all['quotes'];
        $rand_k = array_rand($book_quotes);

        ?></pre>
        <div class="well quote" id="<?= $rand_k; ?>">
          <p><b>“</b><?= $book_quotes[$rand_k]; ?><b>”</b></p>
        </div>
      </div>
      <?php
        } // close IF quotes
      } //close IF quotes file ?>

      <?php
      $book_toc_file = _WR . "/_depo/" . $book . "/toc.json";
      if (is_file($book_toc_file)) {
        $book_toc_json = file_get_contents($book_toc_file);
        $book_toc = json_decode($book_toc_json,true);
      ?>
      <div class="blurbs">
        <h3 class="section-title" id="ToC">
          Table of Contents
        </h3>
        <div class="toc">
          <?php echo $book_toc['html']; ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($bd['blurb_raw']) { ?>
      <div class="blurbs">
        <h3 class="section-title" id="write_up">
          Write-up
        </h3>
        <div class="blurb">
          <?php
          $blurb_html = Markdown($bd['blurb_raw']);
          ?>
          <?= $blurb_html; ?>
        </div>
      </div>
      <?php } ?>

    </div>
  </div>

<?php /* Colors  ?>
 <div class="row" style="margin:10px 0;">
    <div class="col-sm-4" style="background-color:<?= $bd['colors'][0]; ?>;height:4px;"> </div>
    <div class="col-sm-4" style="background-color:<?= $bd['colors'][1]; ?>;height:4px;"> </div>
    <div class="col-sm-4" style="background-color:<?= $bd['colors'][2]; ?>;height:4px;"> </div>
  </div>
  <div class="row" style="margin:10px 0;">
  <img src="/_depo/<?= $bd['uuid']; ?>/spine.png">
  </div>
<?php  */?>

  <div class="row" id="links">
    <div class="col-md-12 text-center">
      <div class="qrcode">
        <img src="/_depo/<?= $bd['uuid']; ?>/qrcode.png" alt="QR Code" width="123" height="123" border="0" />
      </div>
    </div>
  </div>
  </div>
<?php include_once (_WR . '/_bits/footer.php'); ?>
</body>
</html>