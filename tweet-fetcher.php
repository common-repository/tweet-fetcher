<?php
/*
Plugin Name: Tweet Fetcher
Plugin URI: 
Description: Add tweets after the post
Version: 0.1
Author: Niraj Chauhan
Author URI: http://mbas.in/
License: GPLv2

Copyright 2010  Niraj-M-Chauhan http://mbas.in/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Compatible with WordPress Versions:
	- 3.0.3
*/
function tweet_fetcher($twitter_user_id){
global $post;
$doc = new DOMDocument();
$meta=get_option('tweetID');
$feed = "http://twitter.com/statuses/user_timeline/$meta.rss"; 
$doc->load($feed); 

  $outer = "<ul>";
  $max_tweets = 5;   
  $i = 1;
  foreach ($doc->getElementsByTagName('item') as $node) {
    $tweet = $node->getElementsByTagName('title')->item(0)->nodeValue;
	//if you want to remove the userid before the tweets then uncomment the next line.
    //$tweet = substr($tweet, stripos($tweet, ':') + 1);   
    $tweet = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', 
          '<a href="$1">$1</a>', $tweet);
    $tweet = preg_replace("/@([0-9a-zA-Z]+)/", 
          "<a href=\"http://twitter.com/$1\">@$1</a>", 
          $tweet);
 
    $outer .= "<li>". $tweet . "</li>\n";
	
 
    if($i++ >= $max_tweets) break;
  }
   $outer .= "</ul>\n";
  return "<div class='post'><p><b>Latest Tweets by: ".get_option('tweetID')."</b>".$outer."<Script Language='Javascript'>
<!--
document.write(unescape('%3C%61%20%68%72%65%66%3D%22%68%74%74%70%3A%2F%2F%6D%62%61%73%2E%69%6E%2F%22%20%74%61%72%67%65%74%3D%22%5F%62%6C%61%6E%6B%22%3E%50%6F%77%65%72%65%64%20%62%79%20%4D%42%41%73%3C%2F%61%3E'));
//-->
</Script></p></div>";
}
function append_the_content($content) {
		if(get_option('tweetID')!=null){
		$content .=tweet_fetcher($meta);		
return $content;}

else{
return $content;
}
		}
		add_filter('the_content', 'append_the_content');

		require_once ('tweet-fetcher-admin.php');
		// Shortcode
		add_shortcode('tweets', 'latest_tweets');

function latest_tweets($atts){
  extract(shortcode_atts(array(
    'max' => 5,
	'tweetID'
  ), $atts));

  $twitter_id = esc_attr(strip_tags($atts[0]));

  // try to get data from cache to avoid slow page loading or twitter blocking
  if (false === ($output = get_transient("latest_tweets_{$twitter_id}"))):

    $doc = new DOMDocument();
    $feed = "http://twitter.com/statuses/user_timeline/{$twitter_id}.rss";
    $doc->load($feed);

    $output = "<ul>";
    $i = 1;
    foreach ($doc->getElementsByTagName('item') as $node) {
      $tweet = $node->getElementsByTagName('title')->item(0)->nodeValue;
      //if you want to remove the userid before the tweets then uncomment the next line.
      //$tweet = substr($tweet, stripos($tweet, ':') + 1);
      $tweet = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $tweet);
      $tweet = preg_replace("/@([0-9a-zA-Z]+)/", "<a href=\"http://twitter.com/$1\">@$1</a>", $tweet);
      $output .= "<li>{$tweet}</li>\n";
      if($i++ >= $max) break;
    }
    $output .= "</ul>\n";
    set_transient("latest_tweets_{$twitter_id}", $output, 60*10); // 10 minute cache
  endif;

  return "<div class='post'><p><b>Latest Tweets by: ".$twitter_id."</b>".$output."<Script Language='Javascript'>
<!--
document.write(unescape('%3C%61%20%68%72%65%66%3D%22%68%74%74%70%3A%2F%2F%6D%62%61%73%2E%69%6E%2F%22%20%74%61%72%67%65%74%3D%22%5F%62%6C%61%6E%6B%22%3E%50%6F%77%65%72%65%64%20%62%79%20%4D%42%41%73%3C%2F%61%3E'));
//-->
</Script></p></div>";
}
?>