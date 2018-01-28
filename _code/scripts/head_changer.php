<?php
// - seems to work both locally and on server #!/usr/bin/php
error_reporting(E_ERROR);
$request = explode("?anno=",$_REQUEST['parse']);
$book_path = $request[0];
$path = $_SERVER['DOCUMENT_ROOT'].$book_path;

/*
echo "<pre style=\"text-align:left;\">";
echo "\n\n path: ".$path;
echo "\n\n parse: ".$_REQUEST['parse']."\n\n";
print_r($request);

print_r($_SERVER);
print_r($_REQUEST);
print_r($_GET);
print_r($_SESSION);
print_r($_ENV);
print_r($GLOBALS);

echo "</pre>";
*/


// Create the DOC --------------------------------------------------------------
//
$dom  = new DOMDocument();
$dom->loadHTMLFile($path,LIBXML_HTML_NODEFDTD|LIBXML_NOERROR|LIBXML_NOWARNING);


// Find our anchor points ------------------------------------------------------
//
$head = $dom->getElementsByTagName('head')->item(0);
$body = $dom->getElementsByTagName('body')->item(0);

// Create the elements we want to insert ---------------------------------------
//

// Meta charset
$meta_charset = $dom->createElement('meta');
  $meta_charset->setAttribute('charset','utf-8');

$meta_equiv = $dom->createElement('meta');
  $meta_equiv->setAttribute('http-equiv','x-ua-compatible');
  $meta_equiv->setAttribute('content','ie=edge');

$meta_view = $dom->createElement('meta');
  $meta_view->setAttribute('name','viewport');
  $meta_view->setAttribute('content','width=device-width, initial-scale=1.0');

// Stylesheet link
$link_style = $dom->createElement('link');
	$link_style->setAttribute('rel','stylesheet');
	$link_style->setAttribute('rev','stylesheet');
	$link_style->setAttribute('href','/_site/css/insert.css');
	$link_style->setAttribute('media','screen');

// Javascript link
$script_js = $dom->createElement('script','');
	$script_js->setAttribute ('language', 'javascript');
	$script_js->setAttribute ('type', 'text/javascript');
	$script_js->setAttribute ('src', '/_site/js/insert.js');

// Wrap the contents of BODY in a div
$div_wrap = $dom->createElement('div');
	$div_wrap->setAttribute('class','wrap');

// Hello world!
//$p  = $dom->createElement('p','HELLO WORLD!');
//$p->setAttribute('class','helloworld');

// If not already done in original export
// Edit the HTML file to include the cover image --
if (!$dom->getElementById('coverthumb-bo')) {
  $img  = $dom->createElement('img');
  	$img->setAttribute('id','coverthumb-bo');
  	$img->setAttribute('src','../thumbs/cover-320.jpg');
}

// Insert the created elements -------------------------------------------------
//
//$head->appendChild($meta_charset);
$head->insertBefore($meta_equiv,$head->firstChild);
$head->insertBefore($meta_view,$head->firstChild);

$head->appendChild($script_js); // Insert Link to JS
$head->appendChild($link_style); // Insert Link to CSS
//$body->insertBefore($p, $body->firstChild); // Hello world example
if (!$dom->getElementById('coverthumb-bo')) {
	$body->insertBefore($img, $body->firstChild); // Book cover image src
}

// Now, wrap all that shit up in a new DIV -------------------------------------
//
while ($body->childNodes->length > 0) {
    $child = $body->childNodes->item(0);
    $body->removeChild($child);
    $div_wrap->appendChild($child);
}
$body->appendChild($div_wrap);
$body->setAttribute ('id', 'book-read');

// Spit out to the browser -----------------------------------------------------
//

$output = $dom->saveHTML();


// If we've passed an annotation string
// let's wrap it in a fake element, <anno>
if (isset($request[1])) {
  $anno_string = $request[1];
  $output = str_replace($anno_string,"<anno>".$anno_string."</anno>",$output);
}


echo $output;

?>