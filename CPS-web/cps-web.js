credits_remaining = 0;

$(document).ready(function() {
	var btnSpeed = $("#btnConsumeSpeed").button();
	btnSpeed.click(function() {
		incrementCredits();
		consumeSpeed();
	});

	var btnImage = $("#btnConsumeImage").button();
	btnImage.click(function() {
		incrementCredits();
		consumeImage();
	});
});

function consumeSpeed() {
	var postData = {method: "speed"};
	request = $.post("cps-web.php", postData, function(result) {
		$("#textSpeed").html(result);
		decrementCredits();
		// consumeSpeed();
	});
};

function consumeImage() {
	var postData = {method: "image"};
	request = $.post("cps-web.php", postData, function(result) {
		var resObject = $.parseJSON(result);
		$("#textImage").html(resObject["color"] + " " + resObject["speed"]);
		decrementCredits();
		// consumeImage();
	});
};

function incrementCredits() {
	credits_remaining++;
	$("#textRemaining").html(credits_remaining + " credit(s) remaining.");
}

function decrementCredits() {
	credits_remaining--;
	$("#textRemaining").html(credits_remaining + " credit(s) remaining.");
}