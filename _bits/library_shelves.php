<?php
// Library Grid Display --------------
//
if (!isset($shelf_display)) {
  $shelf_display = "horizontal";
}

if ($shelf_display == "vertical") {
  $masonry_class  = "";
  $masonry_opts   = "";
  if (count($disp_index) <= 4) { $wtrigger = "sm"; }
  elseif (count($disp_index) <= 5 ) { $wtrigger = "md"; }
  else {$wtrigger = "lg"; }
  //echo count($disp_index) . " - " . $wtrigger;
  $index_key_column   = " col-md-".round(12/count($disp_index), 0, PHP_ROUND_HALF_UP);
} else {
  $masonry_class  = " masonry js-masonry";
  $masonry_opts   = ' data-masonry-options=\'{ "columnWidth": 10, "gutter": 10 }\'';
  $index_key_column   = "";
}
?>
<div class="row <?=$shelf_display ?>">
<?php

foreach ($disp_index AS $index_key => $bookids) {
  // This check should never fail as we carefully prepared the $disp_index before calling… ;)
  if ($bookids != null AND count($bookids) > 0) {

    echo '<div class="row index_key-group'.$index_key_column.'" id="'.$index_key.'">';

    // Display count of books in Index
    //echo '<div class="index-count">'.count($bookids).'</div>';

    // Section Title - Index Key
    if ($index_parameter == "date") {
      $index_key_print = date('F, Y',strtotime($index_key));
    } elseif ($index_parameter == "stack") {
      // This is a super nasty hack because I am too lazy/dumb/under time pressure
      // to redo the data models…
        $index_key_explode = explode("---",$index_key);
        $index_key_explode[0] = preg_replace('/[\[(.)\]]/','',$index_key_explode[0]);
          $index_key_print = '<a href="stacks/'.$index_key_explode[0].'" title="'.$index_key_explode[1].'">'.$index_key_explode[1].'</a>';
    } else {
      $index_key_print = ucwords($index_key);
    }
    echo '  <h2><div class="index-count">'.count($bookids).' books</div>'.$index_key_print.'</h2>';

    $books_disp = array_intersect_key($ld,$bookids);
    //echo "<pre>";print_r($books_disp); echo "</pre>";
    // -- Make "Shelf" ------------------

    echo '<div class="books'.$masonry_class.'" '.$masonry_opts.'>';

    // Individual Books ---------------------------------------------
    //
    foreach ($books_disp as $k => $book_info) {

      // Classes for sizing based on Page Count
      $sized = which_size($book_info['pages']);

      // Class for Annotated books
      $book_class= "book";
      if ($book_info['annotated']) {
        $book_class .= " annotated";
      }
      if ($book_info['reviewed']) {
        $book_class .= " reviewed";
      }
      if ($book_info['liked']) {
        $book_class .= " liked";
      }

      // Prepare the data attribute for the Anchor
      $data_attr = "";
      if (isset($book_info['isbn'])) {
        $data_attr .= 'data-isbn="'. $book_info['isbn'] .'" '; }
      if (isset($book_info['gdrds'])) {
        $data_attr .= 'data-goodindex_key="'. $book_info['gdrds'] .'" '; }
      if (isset($book_info['m_asin'])) {
        $data_attr .= 'data-mobi-asin="'. $book_info['m_asin'] .'" '; }

      // Print & Open the Anchor Block
      echo '<a class="'. $sized[0] .' '. $sized[1] .' '. $book_class .'" id="'. $book_info['uuid'] .'" href="/library/book/'. $book_info['uuid'] .'" title="'. $book_info['title'] .' - '. $book_info['pages'] .'pp" '. $data_attr .'>';

      // Print the Cover
      echo "<div class=\"cover\">";
      echo " <img src=\"/_depo/".$book_info['uuid']."/cover_320.jpg\" alt=\"Cover of '".$book_info['title']."' by ". $book_info['authors_pp']."\" border=\"0\" />";
      echo "</div>";

      // Print the Book Title and Author
      echo "<div class=\"info\">";
      echo "<div class=\"title\">".$book_info['title']."</div>";
      echo "<div class=\"author\">". $book_info['authors_pp']."</div>";
      echo "</div>";

      // Annotation stats
      if ($book_info['annotated']) {
        echo '<ul class="anno-stats">';
        $p = 1;
        unset($book_info['annotationcounts']['bookmarks']);
        foreach ($book_info['annotationcounts'] as $katy => $vac) {
          if ($vac) {
            $aty = substr($katy,0,1);
            echo '<li class="c-'.$katy.' p-'.$p.'">'.$vac.' '.$aty.'</li>';
            $p++;
          }
        }
        echo '<li style="clear:both;"></li>';
        echo '</ul>';
      }

      // CLose the Anchor Block
      echo "</a>";
    }
    // End Individual Book ------------------------------------------

    echo "</div>";

    // -- End Shelf --------------

    echo '</div>';

  } // end "if bookids"

}
?>
</div>