<?php
add_action('admin_menu', 'tweet_fetch');
function tweet_fetch() {
    add_options_page('Tweet', 'Tweet', 8, 'tweet', 'tweet_fetcher_admin');
}

function tweet_fetcher_admin() {

?>
<h2>Tweet Fetcher admin options</h2>
<i>For getting latest tweets for each post enter twitter</i><b> USER ID.</b></br></br>
<table>
<form method='post' action='options.php' style='margin:0 20px;'>
<?php wp_nonce_field('update-options'); ?>
<tr><td>Twitter UserID:</td><td><input type="text" name="tweetID"  value="<?php echo get_option('tweetID');?>" <?php echo get_option('tweetID'); ?> />
</td></tr>

<input type='hidden' name='action' value='update'/>
<input type='hidden' name='page_options' value='tweetID'/>

<tr><td><p class='submit'>
<input type='submit' name='Submit' value='Update Options &raquo;'/>

</p></td></tr>
</table>
<div><p>
<Script Language='Javascript'>
<!--
document.write(unescape('%3C%61%20%68%72%65%66%3D%22%68%74%74%70%3A%2F%2F%6D%62%61%73%2E%69%6E%2F%22%20%74%61%72%67%65%74%3D%22%5F%62%6C%61%6E%6B%22%3E%50%6F%77%65%72%65%64%20%62%79%20%4D%42%41%73%3C%2F%61%3E'));
//-->
</Script>
</p></div>
</form>
<?php
}
?>