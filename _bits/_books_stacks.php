<?php
// Get the Stacks Indexes
$si_json = file_get_contents(_WR."/_depo/index.stacks-stacks.json");
$si = json_decode($si_json,true);
  //echo "<pre>"; print_r($si); echo "</pre>";
foreach ($bd['stacks_assoc'] as $stack_id => $stack_label) {
  $stacks_books[$stack_id]['stack_label'] = $stack_label;
  foreach ($si[$stack_id] as $kb => $vb) {
    foreach ($si[$stack_id]['books'] as $book_id => $book_id2) {
      $stacks_books[$stack_id]['books'][$book_id] = $ld[$book_id];
      // remove current book from stacks display, to avoid reptition
      if (isset($stacks_books[$stack_id]['books'][$bd['caid']])) {
        unset($stacks_books[$stack_id]['books'][$bd['caid']]);
      }
    }
  }
}


//echo "<pre>";print_r($stacks_books);echo "</pre>";
$dataforlayout = $stacks_books;
  //echo "<pre>"; print_r($dataforlayout); echo "</pre>";
    // The list of books
    if (isset($dataforlayout)) {
?>
<div class="row" id="stacks-book">
  <div class="col-md-12 otherbooks">
    <h3><a href="/library/?i=stack" title="Stacks">Stacks</h3>
<?php
      foreach ($dataforlayout as $stackid => $stack_info) {
        //if ($author_count != 1) {
          echo  "<h4><a href='/library/stacks/".$stackid."' title='".$stack_info['stack_label']."'>".$stack_info['stack_label']."</h4>";
        //}
        $display_books = array_slice($stack_info['books'], 0, 5);
        //echo "<pre>"; print_r($display_books); echo "</pre>";
        include _WR . '/_bits/_t_other_books.php';
        unset($display_books);
      }
?>
  </div>
</div>
<?php
    }
?>