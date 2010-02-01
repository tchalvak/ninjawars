<?php
require_once(LIB_ROOT."specific/lib_chat.php"); // Require all the chat helper and rendering functions.

$private    = false;
$alive      = false;
$page_title = "Mini Chat";
$quickstat  = false;

include SERVER_ROOT."interface/header.php";


$default_limit = 22;
$chatlength = in('chatlength', $default_limit, 'toInt');
$message = in('message', null, 'forChat'); // Essentially no filtering.
$command = in('command');
$sentMessage = in('message');
$chat_submit = in('chat_submit');
$sent = false;
$username = get_username();
$user_id = get_user_id();

// Take in a chat and record it to the database.
if ($user_id) {
	if ($command == "postnow" && $message) {
		send_chat($user_id, $message);
	}
}

// Output section.

echo render_chat_refresh(); // Write out the js to refresh.


/*
<script src='".WEB_ROOT."js/jquery.timeago.js' type='text/javascript'></script>
<script type='text/javascript'>
    jQuery(document).ready(function() {
      jQuery('abbr.timeago').timeago();
    });
</script>
*/


if($chat_submit){ // Js refocus code when chat sent.
	echo '<script type="text/javascript">
		$(document).ready(function(){
			$(".chat-submit #message").focus();
		});
		</script>';
}

echo "<div id='mini-chat'>";

echo render_active_members($sql);

echo render_chat_messages($chatlength);

echo "</div>"; // End of mini_chat div.

echo render_footer($quickstat=null, $skip_quickstat=true);
?>
