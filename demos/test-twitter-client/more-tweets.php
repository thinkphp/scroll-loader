<?php

$page = $_GET['page'] && isset($_GET['page']) ? $_GET['page'] : 1;

$amount = $_GET['amount'] && isset($_GET['amount']) ? $_GET['amount'] : 10;

$username = $_GET['username'] && isset($_GET['username']) ?  $_GET['username'] : 'mootools';

require_once('twitter.statuses.class.php');

$twitter = new TwitterStatus($username,$amount);

$result = $twitter->user_timeline($page);

if($result && is_array($result)) {

   $out = '';
   foreach($result as $r) {
     $out .= '<li>';
     $out .='<span class="status-body">';
     $out .= '<span class="status-content">';
     $out .= '<span class="status-entry">';
     $out .= tweetify($r['text']);  
     $out .= '</span>';
     $out .= '</span>';
     $out .= '<span class="meta entry-meta">';
     $out .= $r['created_at'];
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
