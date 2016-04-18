function leave_clan() {
	if (confirm("Do you really want to exit the clan?")) {
		window.location = "/clan/leave";
	}

	return false;
}

$(function () {
	$("#leave-clan").click(function() {
		leave_clan();
		return false;
	});
});
