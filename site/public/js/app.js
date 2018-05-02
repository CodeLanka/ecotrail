var authUser;
$('document').ready(function() {
	var loggedIn = false;

	var provider = new firebase.auth.GoogleAuthProvider();

	firebase.auth().onAuthStateChanged(function(user) {
		if(user != null) {
			authUser = user;
			$("#login-info").html("<img class='uihold' src='"+ user.photoURL + "' />" + user.displayName + "");
			// record(user);
			$(".login-form").hide();
			$(".loading-form").hide();
			fetch(user.email, window.location.pathname, window.location.hash);
			$("#content").html("<h5>The trail will appear soon...</h5>");
			loggedIn = true;
		} else {
			$(".loading-form").hide();
			$(".login-form").show();
		}
		
	});


	$("#login").click(function() {
		if($("#agree").is(":checked")) {
			if(!loggedIn) {
				firebase.auth().signInWithRedirect(provider);
			}
		} else {
			alert("You need to agree to the terms and conditions to play");
		}
		
	});

	var gs = firebase.storage().refFromURL("gs://keyhole-quiz.appspot.com/GDG Logo_120_24.png");
	gs.getDownloadURL().then(function(url) {
		$("#gdg-logo").attr("src", url);
	});
});

// var backend = "http://localhost:8080/";
var backend = "https://io-18-quiz.appspot.com/";

function fetch(email, path, hash) {
	$.ajax({
		type: 'post',
		url: backend,
		data: "u="+ email +"&k="+ path,
		success: function(data, status, xhr) {
			$("#content").html(data);
			var questionString = xhr.getResponseHeader("X-Question-Number");
			if(questionString != null) {
				var question = parseInt(questionString);
				
				var qPaths = ["First", "Second", "Third", "Fourth", "Final", "You have trekked the"];
				$("#questionNumber").html(qPaths[question - 1] + " path");
				populateDescription(question);
				recordDB(email, path, question);
			} else {
				$("#questionNumber").html("Lost");
				recordDB(email, path, 0);
			}
			
		}
	});
}


function populateDescription(step) {
	$.ajax({
		type: 'get',
		url: '/desc' + step,
		success: function(data) {
			$("#desc-content").html(data);
		}
	});
}

function recordDB(email, hash, question) {
	firebase.database().ref("users/" + authUser.uid + "/_details").update({
		name: authUser.displayName,
		email: authUser.email,
		photo: authUser.photoURL
	});

	firebase.database().ref("users/" + authUser.uid + "/" + question).push({
		name: authUser.displayName,
		email: authUser.email,
		t: (new Date()).getTime()
	});
}