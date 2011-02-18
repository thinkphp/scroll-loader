<?php

$page = $_GET['page'] && isset($_GET['page']) ? $_GET['page'] : 1;

$search = $_GET['search'] && isset($_GET['search']) ?  $_GET['search'] : 'mootools';

require('twitter.api.class.php');

$twitter = new Twitter('thinkphp','no-PASSWORD');

$ob = $twitter->search(array('q'=>$search,'page'=>$page));

$res = $ob->results;

if($res && is_array($res)) {

   $out = '';
   foreach($res as $r) {
     $out .= '<li>';
     $out .= "<div class='image_profile'><img src='$r->profile_image_url' alt='cover'></div>";
     $out .='<span class="status-body">';
     $out .= '<span class="status-content">';
     $out .= '<span class="status-entry">';
     $out .= tweetify($r->text);  
     $out .= '</span>';
     $out .= '</span>';
     $out .= '<span class="meta entry-meta">';
     $out .= $r->created_at;
     $out .= '</span>';
     $out .= '</li>';
   }
      } else {
     $out = 'No found results.';
   }
   function tweetify($text) {
       $text = preg_replace("/(https?:\/\/[\w\-:;?&=+.%#\/]+)/i", '<a href="$1">$1</a>',$text);
       $text = preg_replace("/(^|\W)@(\w+)/i", '$1<a href="http://twitter.com/$2">@$2</a>',$text);
       $text = preg_replace("/(^|\W)#(\w+)/i", '$1#<a href="http://search.twitter.com/search?q=%23$2">$2</a>',$text); 
      return $text;
   }
   echo$out;
?>
