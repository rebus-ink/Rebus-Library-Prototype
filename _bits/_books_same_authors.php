    <?php
    // Set "All Authors' Other Books' IDs"
    //print_r($authors);
    //echo $bd['caid']."\n";
    $aaobids = array();
    foreach ($authors as $k => $author) {
      $book_ids = $aia[$author['auid']]['books'];
      // Remove the current book from the array of all books by this author
      if(($key = array_search($bd['caid'], $book_ids)) !== false) {
        unset($book_ids[$key]);
      }
      // If they have any left, populate the array
      if ( count($book_ids) != 0 ) {
        //$aaobids[$author['auid']]['books'] = $book_ids;
        foreach($book_ids AS $kid => $vid) {
          $aaobids[$author['auid']]['books'][$kid] = $ld[$vid];
        }
        $aaobids[$author['auid']]['name'] = $aia[$author['auid']]['name'];
      }
      // If not, nothing.
      // else {  }
    }
    //echo "Authors of this book of whom we have more books: ".count($aaobids)."\n";
    //echo "Authors of this book: ".count($authors)."\n";
    $author_count           = count($authors);
    $author_w_book_count    = count($aaobids);
    $author_w_book_count_w  = convert_number_to_words($author_w_book_count);
    // If any of the authors have more books, let's continue
    if (count($aaobids) > 0) {
?>
 <div class="row" id="author-book">
    <div class="col-md-12 otherbooks">
<?php
      if ($author_count == 1) {
        echo "<h3>Also by this author:</h3>";
      } else {
        echo "<h3>Also by ".$author_w_book_count_w." of the authors:</h3>";
      }

      //echo "<pre>"; print_r($aaobids); echo "</pre>";
      $dataforlayout = $aaobids;
    }
    // If not, nothing more to see here.
    else {
      if (count($authors) > 1) {
        //echo "<h3>We have no other books by any authors of this book.</h3>";
      } else {
        //echo "<h3>We have no other books by this author.</h3>";
      }
    }
    // The list of books
    if (isset($dataforlayout)) {
      foreach ($dataforlayout as $kauid => $vainfo) {
        if ($author_count != 1) {
          echo  "<h4>".$vainfo['name']."</h4>";
        }
        $display_books = array_slice($vainfo['books'], 0, 5);
        //echo "<pre>"; print_r($display_books); echo "</pre>";
        include _WR . '/_bits/_t_other_books.php';
        unset($display_books);
      }
?>
    </div><!-- .otherbooks -->
  </div><!-- #author-book -->
<?php
    }
?>