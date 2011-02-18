ScrollLoader
============

Loads more content when a user reaches the end of a page. Fires an event when the user reaches a certain boundary specified by 'area' option.

![Screenshot](http://farm6.static.flickr.com/5138/5455884682_d77406366f_z.jpg)

First you must to include the JS file in the head of your HTML document.

          #HEAD
          <script type="text/javascript" src="mootools-core.js"></script>
          <script type="text/javascript" src="Class.Binds.js"></script>
          <script type="text/javascript" src="ScrollLoader.js"></script>

How to use
----------
 
          #JS
          window.addEvent('domready', function(){
              var page = 2, url = "more-tweets.php";
              new ScrollLoader({
                  onScroll: function() {
                     var self = this;
                     $('content_tweets').adopt(new Element('img',{src: 'ajax-loader.gif', id: 'loader'}));
                     self.detach();
                    (function() {
                        //let's make a request for additional content
                        new Request.HTML({url: url,
                           onSuccess: function(responseTree, responseElements, responseHTML, reponseJavaScript) {
                                 $('loader').destroy();
                                 if(responseHTML == 'No found results.') {
                                         $('timeline').set('html', $('timeline').get('html') + 'Older tweets are temporarily unavailable.');
                                         return;   
                                 } 
                                 $('timeline').set('html', $('timeline').get('html') + responseHTML);
                                 self.attach();
                                 page++;
                            }
                        }).get({'page': page, 'amount': "14",'username': "mootools"});
                    }).delay(1000);                            
                  }
              });  
          });

Note: 
-----

This plugin is part of [PowerTools](http://cpojer.net/PowerTools/)

Demos:
------

You can view in action:

* [http://thinkphp.github.com/scroll-loader/](http://thinkphp.github.com/scroll-loader/)
* [http://thinkphp.ro/apps/js-hacks/scroll-loader/demos/test-twitter-client/](http://thinkphp.ro/apps/js-hacks/scroll-loader/demos/test-twitter-client/)
* [http://thinkphp.ro/apps/js-hacks/scroll-loader/demos/test-twitter-search/](http://thinkphp.ro/apps/js-hacks/scroll-loader/demos/test-twitter-search/)
