<!DOCTYPE html>
<html class="html override cmon" id="fs-container">
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<script type='text/javascript' src='/_site/js/moment.min.js'></script>
<script type='text/javascript' src='/_site/js/mousetrap.min.js'></script>
<script type='text/javascript' src='/_site/js/jquery.js'></script>

<title>Library - Annotations</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
@import url('/_site/css/_Fonts.css');
  body {
    background-color: #000000;
    font-family: 'Merriweather', serif;
    }
  html, ::backdrop, :-webkit-full-screen, :fullscreen, :full-screen, :fullscreen::backdrop {
    background-color: #000000 !important;
  width: 100% !important;
  height: 100% !important;
    }
html.html.override.cmon {
  background-color: black;
}
a, a:active, a:visited {
  text-decoration: none;
  }
  div {
    margin: 0;
    padding: 0;
    }

  div#annotation {
    width: 100%;
    overflow:hidden;
    }
    div#annotation div#text {
      font-weight: lighter;
      font-size: 4em;
      margin: .5em 1em 2em 1em;
      padding: 0 0 0 0;
      line-height: 1.5em;
      height: 70vh;
      overflow: scroll;
    }
    div#text::first-letter {
      font-size: 200%;
      text-transform: uppercase;
      }
    div#text::-webkit-scrollbar {
      visibility: hidden;
      }
    body::-webkit-scrollbar {
      visibility: hidden;
      }
  div#book {
    position: absolute;
    bottom: 40px;
    right: 60px;
    width: 100%;
    padding: 1em 0 1em 0;
    background-color: #000;
    }
    div#book img#cover {
      float:right;
      }
    div#book div#bookinfo {
    /* visibility:hidden; */
    text-align: right;
    margin: 45px 120px 0 0;
    }
    div#book:hover div#bookinfo {
    /*visibility:visible;*/
    }
    div#book div#title {
    font-size: 2em;
    font-weight: lighter;
    }
    div#book div#author {
    font-size: 1em;
    font-weight: lighter;
    font-style: italic;
    }
button {
  width: 40px;
  height: 40px;
  background-color: #000;
  color:#666;
  border: none;
  font-size:2em;
  }
button:hover {
  color: #ff0;
  }
button#goto-lib {
  position:absolute;
  top: 0;
  left: 0;
  }
button#enter-exit-fs {
  position: absolute;
  top: 0;
  right: 0;
  font-size:1.5em;
  }
button#next {
  position: absolute;
  top: 100px;
  right: 0;
  }

</style>
</head>
<body id="fs">
<button id="goto-lib"       onclick="window.location.href='/library'">𝍂</button>
<button id="enter-exit-fs"  onclick="enterFullscreen()">⿹</button>
<button id="next"           onclick="getAnno()">≫</button>
<div id="box">
  <!--div id="calen"></div-->
  <div id="annotation">
    <div id="text"><a href=""></a><br />&nbsp;<br /></div>
  </div>
  <div id="book">
    <a id="coveruri" href=""><img id="cover" src="" /></a>
    <div id="bookinfo">
      <div id="title"></div>
      <div id="author"></div>
    </div>
  </div>


<script>

function launchFullscreen(element) {
  if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}


var d;
var anno_text;
var book_title;
var txtcol;
var s_night = 20;
var e_night = 6;
var quoteData;



//function setAnno(anno_is_uri,anno_text,book_title,book_author,book_uri,book_cover) {
function setAnno(anno_text,book_title,book_author,book_uri,book_cover) {

  d = new moment();
  txtcol = setColor(d.format("H"),s_night,e_night);
  txtcol2 = setColor2(d.format("H"),s_night,e_night);

  //Print the annotation, fading in and out
  $('#box').fadeOut(1500, function() {
      //$("#text a[href]").attr("href",anno_is_uri).html(anno_text).css("color",txtcol);
      $("#text a[href]").attr("href",book_uri).html(anno_text).css("color",txtcol);
      $("#title").html(book_title).css("color",txtcol2);
      $("#author").html(book_author).css("color",txtcol2);
      $("#cover[src]").attr("src",book_cover);
      $("#coveruri[href]").attr("href",book_uri);
      $('#box').fadeIn(1500);
  });

}



function getAnno() {
  jQuery(document).ready(function($) {
    $.ajax({
      url : "/library/annotations/random.json.php", 
      dataType : "json",
      async: false,
      success : function(parsed_json) {
        window.quoteData   = parsed_json;
        var anno_text   = parsed_json['quote']['text'];
        var book_title  = parsed_json['book']['title'];
        var book_author = parsed_json['book']['author'];
        var book_uri    = parsed_json['book']['uri'];
        var book_cover  = parsed_json['book']['cover_path'];
        eval(setAnno(anno_text,book_title,book_author,book_uri,book_cover));
        }
      })
    })
  }



function shareQuote(quoteData) {
  jQuery(document).ready(function($) {
    $.ajax({
      url        : "/dev/catch.php",
      dataType   : 'json',
      contentType: 'application/json; charset=UTF-8', 
      data       : JSON.stringify(window.quoteData),
      type       : 'POST',
      //complete   : callback // etc
      success: function(quoteData){
        console.log(quoteData);
      }
    })
  })
}



function setColor(hour,s_night,e_night) {
  var col;
  if(hour >= s_night || hour <= e_night)
    // col = "#ff7e00"; // amber
    col = "#FF4500"; // orange red
  else
    col = "#F7F2E7"; // ffffff light
  return col;
  }
function setColor2(hour,s_night,e_night) {
  var col;
  if(hour >= s_night || hour <= e_night)
    col = "#8B2600"; // amber
  else
    col = "#E7E2D7"; // 999999 - dark
  return col;
  }
</script>
<?php
if (isset($_GET['m'])) {
  $t = $_GET['m'];
} else {
  $t = 10;
}

?>
<script>
//setInterval(getAnno, 1200000); // 20 minutes

setInterval(getAnno, <?php echo $t; ?> * 60 * 1000);
//setInterval(getAnno, 5 * 1000);
getAnno();

</script>
<script>
document.cancelFullScreen = document.webkitExitFullscreen || document.mozCancelFullScreen || document.exitFullscreen;

var elem = document.querySelector(document.webkitExitFullscreen ? "#fs" : "#fs-container");

document.addEventListener('keydown', function(e) {
  switch (e.keyCode) {
    case 13: // ENTER. ESC should also take you out of fullscreen by default.
      e.preventDefault();
      document.cancelFullScreen(); // explicitly go out of fs.
      break;
    case 70: // f
      enterFullscreen();
      break;
  }
}, false);

function toggleFS(el) {
  if (el.webkitEnterFullScreen) {
    el.webkitEnterFullScreen();
  } else {
    if (el.mozRequestFullScreen) {
      el.mozRequestFullScreen();
    } else {
      el.requestFullscreen();
    }
  }

  el.ondblclick = exitFullscreen;
}

function onFullScreenEnter() {
  console.log("Entered fullscreen!");
  elem.onwebkitfullscreenchange = onFullScreenExit;
  elem.onmozfullscreenchange = onFullScreenExit;
};

// Called whenever the browser exits fullscreen.
function onFullScreenExit() {
  console.log("Exited fullscreen!");
};

// Note: FF nightly needs about:config full-screen-api.enabled set to true.
function enterFullscreen() {
  console.log("enterFullscreen()");
  elem.onwebkitfullscreenchange = onFullScreenEnter;
  elem.onmozfullscreenchange = onFullScreenEnter;
  elem.onfullscreenchange = onFullScreenEnter;
  if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
  } else {
    if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else {
      elem.requestFullscreen();
    }
  }
  document.getElementById('enter-exit-fs').onclick = exitFullscreen;
}

function exitFullscreen() {
  console.log("exitFullscreen()");
  document.cancelFullScreen();
  document.getElementById('enter-exit-fs').onclick = enterFullscreen;
}


//Mousetrap.bind('m', getAnno());
Mousetrap.bind('right', function() { getAnno(); });
//Mousetrap.bind('up', function() { shareQuote(quoteData); });
</script>


</div>
</body>
</html>