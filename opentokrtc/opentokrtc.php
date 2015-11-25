<?php

/**
 * Name: HZ Multiparty Video Chat by OpenTokRTC.com 
 * Description: A simple wrapper for multiparty videochat provided by OpenTokRTC.com 
 * Version: 1.0
 * Author: Paolo Wave Tacconi
 */

function opentokrtc_load() {
    register_hook('app_menu', 'addon/opentokrtc/opentokrtc.php', 'opentokrtc_app_menu');
}

function opentokrtc_unload() {
    unregister_hook('app_menu', 'addon/opentokrtc/opentokrtc.php', 'opentokrtc_app_menu');
}

function opentokrtc_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="opentokrtc">Multiparty Video Chat</a></div>';
}

//function get_video_id() {
//    $pageuri = $_SERVER["REQUEST_URI"];
//    $video_id = "";
//    if (strpos($pageuri,ADDON_NAME."/".VIDEO_ID_DELIMITER) != false) {
//        $id_value = substr(strstr($pageuri,VIDEO_ID_DELIMITER),strlen(VIDEO_ID_DELIMITER));
//        $video_id = $id_value;
//    }
//    return $video_id;
//}

function opentokrtc_module() {}

function opentokrtc_content(&$a) {
	//$pageuri = $_SERVER["REQUEST_URI"];
	//if (strpos($pageuri,'opentokrtc') != false) {
		$a->page['htmlhead'] .= '<script src="/addon/opentokrtc/jquery-1.10.2.min.js"></script>';
		$o .= <<< EOT
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><img src="/addon/opentokrtc/opentokrtc.png" width="40px" height="38px"> Multiparty Video Chat</h3>
			</div>
			<div class="panel-body">
				<p><i>Powered by OpenTokRTC</i></p>
				<p>&nbsp;</p>
				<p>Type the name of the chat room you want to join.</p> 
				<div id="roomContainer">
					<form id="roomForm"> 
						<input id="roomName" class="std-input-text" type="text" placeholder="Enter a room name"/> 
						<a href="#" id="roomButton" class="btn btn-primary">Join</a>
					</form>
				</div>
			</div>
		</div>
		<script src="/addon/opentokrtc/opentokrtc.js" type="text/javascript"></script>
EOT;
	//}
	return $o;
}
