<?php
/*
Plugin Name: GeekList Widget Account
Plugin URI: http://wartdev.com/
Description: Widget for Displays a Account Geekli.st on your Wordpress page 
Author: Rojas Wilmer rojaswilmer@gmail.com
Version: 1
Author URI: http://wartdev.com/
*/
 
class GeekListAccount extends WP_Widget
{
    
  function GeekListAccount()
  {
    $widget_ops = array('classname' => 'GeekListAccount', 'description' => 'Displays a Account Geekli.st on your Wordpress page' );
    $this->WP_Widget('GeekListAccount', 'Geekli.st Display Account Profile', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'user_account' => '' ) );
    $title = $instance['user_account'];
?>
<p>
	<label for="<?php echo $this -> get_field_id('user_account'); ?>">User Geekli.st:
		<input class="widefat" id="<?php echo $this -> get_field_id('user_account'); ?>" name="<?php echo $this -> get_field_name('user_account'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
	</label>
</p>
<?php
}

function update($new_instance, $old_instance)
{
$instance = $old_instance;
$instance['user_account'] = $new_instance['user_account'];
return $instance;
}

function widget($args, $instance)
{
extract($args, EXTR_SKIP);
$account = empty($instance['user_account']) ? ' ' : apply_filters('widget_title', $instance['user_account']);
$url= "http://www.geekli.st";
$url_account = $url."/".$account;
$json_account_url = "http://geekli.st/".$account.".json";
$c = curl_init($json_account_url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
$geeklist_account = json_decode(curl_exec($c), true);
curl_close($c);
echo $before_widget;
if (!empty($account))
$imgaccount = $geeklist_account[avatar][large];
$number_cards = $geeklist_account[stats][number_of_cards];
$number_contributions = $geeklist_account[stats][number_of_contributions];
$number_of_creds = $geeklist_account[stats][number_of_creds];
$number_of_highfives = $geeklist_account[stats][number_of_highfives];

echo "<style>
#geeklist_profile{
    text-align:center;
}
.titledata{
    font-weight: bold;
}
</style>
        <div id='geeklist_profile'>
      <a href='".$url."'><img src='".plugins_url('geeklist-widget-account/img/logo.png')." '/></a><br><hr>
      <div><img src='$imgaccount'/></div>
      <div><h3>$geeklist_account[name]</h3>
      <p>@$geeklist_account[screen_name]</p>";
      foreach ($geeklist_account[social_links] as $social_link) {
          echo "<p><a href='$social_link'>$social_link</a></p>";
      }
          echo "<p><span class='titledata'>Cards:</span> $number_cards
                 | <span class='titledata'>Contributions:</span> $number_contributions
                 | <span class='titledata'>Creds:</span> $number_of_creds
                 | <span class='titledata'>Highfives:</span> $number_of_highfives</p>
               ";
      echo "Follow me on <a href='$url_account'>$url_account</a></div>";
      echo "</div>";
echo $after_widget;
}


}
add_action( 'widgets_init', create_function('', 'return register_widget("GeekListAccount");') );
?>