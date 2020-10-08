var option = document.createElement("option");
option.value = "My";
option.text = "Aureliano Martini";

require(['jquery', 'core/ajax', 'core/notification'], function($, ajax, notification) {
	var promises = ajax.call([
		{ methodname: 'get_userlist', args: { cohortid: 1 } }
	]);
	promises[0].done(function(response) {
		document.getElementById('id_userid').innerHTML = "";
		response.forEach(function(item, index) {
			var option = document.createElement("option");
			option.value = item.id;
			option.text = item.firstname + " " + item.lastname;
			document.getElementById('id_userid').add(option);
			console.log(item);		
		});
	}).fail(notification.exception);
});