<div class="row" id="similar-book">
  <div class="col-md-12 otherbooks">
    <h3>Similar books</h3>
<?php
  // Get the Tags Indexes
  $tit_json = file_get_contents(_WR."/_depo/index_pp.tags-tags.json");
  $tit = json_decode($tit_json,true);

  // Initiate Counter
  $tag_books['count']['books'] = 0;

  // Count how many tags we have on this specific book
  $tag_books['count']['tags'] = count($bd['tags_assoc']);

  // Loop through each of the tagIDs we have on this specific book
  foreach ($bd['tags_assoc'] as $tagid => $tag) {

    // Get the books associated with a tag from the Tags-perTag Index
    $tag_books['tags'][$tagid] = $tit['tag'][$tagid];

    // Count total of books
    $tag_books['count']['books'] = $tag_books['count']['books'] + $tit['tag'][$tagid]['count'];

    // Make an array of unique bookIDs, with count for occurence overall
    $occ = 0;
    foreach ($tag_books['tags'][$tagid]['books'] as $k => $bid) {
      //$tag_books['tags'][$tagid]['books'][$bid] = $ld[$bid];
      $tag_books['ubooks']['booksi'][$bid]['incs'][] = 1;
      $tag_books['ubooks']['booksi'][$bid]['count'] = count($tag_books['ubooks']['booksi'][$bid]['incs']);
      $tag_books['ubooks']['books']['ids'][$bid] = $tag_books['ubooks']['booksi'][$bid]['count'];
    }
    $tag_books['ubooks']['books']['counts'] = $tag_books['ubooks']['books']['ids'];
    ksort($tag_books['ubooks']['books']['ids']);
    ksort($tag_books['ubooks']['books']['counts']);
    arsort($tag_books['ubooks']['books']['counts']);
    $tag_books['count']['ubooks'] = count($tag_books['ubooks']['books']);
  }
  unset($tag_books['ubooks']['booksi']);
  //print_r($tag_books['ubooks']['books']['counts']);
  //print_r($aaobids);

  // Remove other books by the same other from our "related" list
  foreach ($aaobids as $aid => $subs) {
    foreach ($subs['books'] as $id => $stuff) {
      //echo $id . " - ";
      if (isset($tag_books['ubooks']['books']['counts'][$id])) {
        unset($tag_books['ubooks']['books']['counts'][$id]);
        //echo "unset ". $id . "\n";
      } else {
        //echo "kept ". $id . "\n";
      }
    }
  }
  // Remove this book from the list too
  //echo "this is book #".$bd['caid']."\n";
  if (isset($tag_books['ubooks']['books']['counts'][$bd['caid']])) {
    unset($tag_books['ubooks']['books']['counts'][$bd['caid']]);
  }

  // Finalize Similar Books
  $similar_books = array_slice($tag_books['ubooks']['books']['counts'], 0, 5,TRUE);
  //echo "<pre>"; print_r($similar_books); echo "</pre>";
  // Add each book's data to the array
  foreach ($similar_books as $bid => $cc) {
    unset($similar_books[$bid]);
    $similar_books[$cc] = $ld[$cc];
  }
  //print_r($similar_books);
$display_books = $similar_books;
include _WR . '/_bits/_t_other_books.php';
unset($display_books);
?>
  </div>
</div>