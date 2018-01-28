<?php
echo  "<div class=\"otherbooks\">";
foreach ($display_books as $kbid => $book_info) {
  echo  "<a class=\"cover\" id=\"". $book_info['uuid'] ."\" href=\"/library/book/". $book_info['uuid'] ."\" title=\"". $book_info['title'] ." - ". $book_info['pages'] ."pp\">";
  echo    "<img class=\"\" src=\"/_depo/".$book_info['uuid']."/cover_128x128.jpg\" alt=\"Cover of '".$book_info['title']."' by ". $book_info['authors_pp']."\" border=\"0\" />";
  echo  "</a>";
}
echo  "</div>";
?>