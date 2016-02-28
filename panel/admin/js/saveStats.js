$(document).ready(function()
{
	$("#saveStats").click(function() /* Save stats on button click */
	{
		$.get("js/saveStats.php");
		console.log("Saved!");
	});

	var time=30; //minutes

	window.setInterval(function() /* Save stats every 30 minutes */
	{
		$.get("js/saveStats.php");
		console.log("Saved!");
  	}, time*60*1000);
});
