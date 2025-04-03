$(function()
{
	function timeChecker()
	{
		setInterval(function()
		{
			var storedTimeStamp = sessionStorage.getItem("lastTimeStamp");
			timeCompare(storedTimeStamp);
		},3000);
	}

	function timeCompare(timeString)
	{
		var currentTime = new Date();
		var pastTime = new Date(timeString);
		var timeDiff = currentTime - pastTime;
		var minPast = Math.floor((timeDiff/60000));

		if(minPast == 3)
		{
			sessionStorage.removeItem("lastTimeStamp");
			window.location = "logout";
			return false;
		}else{
			console.log(currentTime + " - " + pastTime + " - " + minPast + " min passed");
		}
	}

	$(document).mousemove(function()
	{
		var timeStamp = new Date();
		// when you move the mouse, the seconds increment
		sessionStorage.setItem("lastTimeStamp",timeStamp);		
	});

	timeChecker();
})