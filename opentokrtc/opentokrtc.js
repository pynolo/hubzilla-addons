$("#roomName").focus();
$("#roomForm").submit(false);

function goToRoom(){
	console.log("goToRoom");  
	var roomName = $.trim($("#roomName").val());
	console.log(roomName);  
	if(roomName.length <= 0 ) return;
	$("#roomContainer").fadeOut('slow');
	window.location = "https://opentokrtc.com/" + roomName;
	console.log("https://opentokrtc.com/" + roomName);  
}

$('#roomButton').click(function(){
    goToRoom();
    return false;
});

$('#roomName').keypress(function(e){
	if (e.keyCode === 13) goToRoom();
});

