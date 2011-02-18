<?php
$username = $_GET['username'] && isset($_GET['username']) ? $_GET['username'] : 'mootools';
$page = $_GET['page'] && isset($_GET['page']) ? $_GET['page'] : 1;
$amount = 14;
require('twitter.statuses.class.php');
$twitter = new TwitterStatus($username,14);
$result = $twitter->user_timeline($page);
echo'<!--';
echo'<pre>';
print_r($result);
echo'</pre>';
echo'-->';
if($result && is_array($result)) {

   $out = '<ol id="timeline" class="statuses">';
   $header = '<li><img src="'.$result[0]['user']['profile_image_url'].'">'.
                  '<div><strong>@'.$result[0]['user']['screen_name'].'</strong></div>'.
                  '<div>'.$result[0]['user']['location'].'</div>'.
                  '<div><a href="'.$result[0]['user']['url'].'">'.$result[0]['user']['url'].'</a></div>'.
                  '<div>'.$result[0]['user']['description'].'</div>'.
                  '</li>';
   $out .= $header;
   foreach($result as $r) {
     $out .= '<li>';
     $out .= '<span class="status-body">';
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
   $out .= '</ol>';
   
   } else {
     $out = 'No found results.';
   }
   function tweetify($text) {
       $text = preg_replace("/(https?:\/\/[\w\-:;?&=+.%#\/]+)/i", '<a href="$1">$1</a>',$text);
       $text = preg_replace("/(^|\W)@(\w+)/i", '$1<a href="http://twitter.com/$2">@$2</a>',$text);
       $text = preg_replace("/(^|\W)#(\w+)/i", '$1#<a href="http://search.twitter.com/search?q=%23$2">$2</a>',$text); 
      return $text;
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>ScrollLoader for Twitter Search</title>
<style type="text/css">
* {margin:0;padding:0;}
html,body{font-family: georgia,arial,verdana;font-size: 25px;background: #111}
form{-moz-border-radius:5px;-webkit-border-radius:5px;background:#00B7FF;padding: 5px;width: 60%;margin-left: 220px;margin-bottom: 10px;}
form label {font-size: 15px}
div#content_tweets{width: 567px;background: #fff;}
ol#timeline {padding: 10px}
ol.statuses {font-size:14px;list-style:none outside none;}
ol.statuses > li:first-child {border-top:0 none;}
ol.statuses li.latest-status {border-top-width:0;line-height:1.5em;padding:1.5em 0 1.5em 0.5em;}
ol.statuses li {padding-left:0.5em;}
ol.statuses li {line-height:20px;margin-bottom: 20px}
.meta {color:#999999;display:block;font-size:11px;}
ol.statuses li.status, ol.statuses li.direct_message {border-bottom:1px solid #EEEEEE;line-height:20px;padding:10px 0 8px;position:relative;}
ol.statuses .latest-status .entry-content {font-size:1.77em;}
div#content_tweets {padding:0 220px;}
div#content_tweets {word-wrap:break-word;}
li:hover {background: #F7F7F7}
a{color: #2FC2EF}
ol .image_profile img {margin-left:0;float: left;padding-right: 10px}
#content_tweets img#loader {margin-left: 270px}
#logo a {background: url("logo.png") no-repeat scroll 20px 9px transparent;color: #FFFFFF;display: block;height: 50px; margin-left: 205px;outline: medium none;text-indent: -9999px;width: 240px;}
#ft{font-size:80%;color:#888;margin:2em 0;font-size: 16px;padding-left: 230px}
#ft p a{color:#2FC2EF;}
</style>
</head>
<body>
<div id="logo"><a href="#">Twitter</a></div>
<form id="f" name="f">
  <label for="username">Username:</label><input type="text" id="username" name="username" value="<?php echo$_GET['username'];?>"/><input type="submit" value="Grab">
</form>  
<div id="content_tweets"><?php echo$out; ?></div>
<div id="ft"><p>Written by @<a href="http://twitter.com/thinkphp">thinkphp</a> | source on <a href="https://github.com/thinkphp/scroll-loader">Github</a> |<a href="ScrollLoader.js">ScrollLoader</a> as part of <a href="http://cpojer.net/PowerTools">PowerTools</a></p></div>
   <script src="http://www.google.com/jsapi?key=ABQIAAAA1XbMiDxx_BTCY2_FkPh06RRaGTYH6UMl8mADNa0YKuWNNa8VNxQEerTAUcfkyrr6OwBovxn7TDAH5Q"></script>
   <script type="text/javascript">google.load("mootools", "1.3");</script>
   <script type="text/javascript" src="Class.Binds-min.js"></script>
   <script type="text/javascript" src="ScrollLoader-min.js"></script>
   <script type="text/javascript">
         window.addEvent('domready', function(){
               if(Browser.ie) {$('content_tweets').setStyle('width','100%');document.getElement('form').setStyle('width','80%');}
               var page = 2,
                   url = "more-tweets.php";
               new ScrollLoader({
                   onScroll: function() {
                        var self = this;
                        $('content_tweets').adopt(new Element('img',{src: 'ajax-loader.gif', id: 'loader'}));
                        self.detach();
                        (function() {
                           //let's make a request for additional content
                           new Request.HTML({url: url,
                               onSuccess: function(responseTree,responseElements,responseHTML,reponseJavaScript) {
                                      $('loader').destroy();
                                      if(responseHTML == 'No found results.') {
                                         $('timeline').set('html', $('timeline').get('html') + 'Older tweets are temporarily unavailable.');
                                         return;   
                                      } 
                                      $('timeline').set('html', $('timeline').get('html') + responseHTML);
                                      self.attach();
                                      page++;
                               }
                           }).get({'page': page, 'amount': "<?php echo$amount;?>",'username': "<?php echo$username;?>"});
                        }).delay(1000);                            
                   }
               });  
         });
   </script>    
</body>
</html>
