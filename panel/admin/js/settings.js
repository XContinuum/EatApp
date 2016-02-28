$(document).ready(function()
{
	$("#btn_unvalidated").click(function() /* show unvalidated list */
	{
		$('#validated').hide();
		$('#not_validated').show();
	});

	$("#btn_validated").click(function() /* show validated list */
	{
		$('#validated').show();
		$('#not_validated').hide();
	});

	$(".action_validate").click(function() /* validate chain */
	{
		var index=$(this).index(".action");
		var var_id=$(".chain_ID").eq(index).val();

		$.get("manipulate_data.php?id="+var_id+"&type=validate");
	});

	$(".action_unvalidate").click(function() /* unvalidate chain */
	{
		var index=$(this).index(".action");
		var var_id=$(".chain_ID").eq(index).val();

		$.get("manipulate_data.php?id="+var_id+"&type=unvalidate");
	});

	$(".attach_button").change(function() /* attach menu to chain */
	{
    	readURL(this,$(this).index(".attach_button"));
	});

	$(".login").click(function() /* forced login into chain profile */
	{
		var index=$(this).index(".login");
		var var_id=$(".chain_ID").eq(index).val();

		window.location.href="forced_login.php?id="+var_id;
	});
});
/*

	ONLOAD EVENT+++++++++

*/
function readURL(input,index)
{
    if (input.files && input.files[0])
    {
        var reader=new FileReader();

        reader.onload=function(e)
        {
            attach_menu(index);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function attach_menu(index)
{
	var datastring=new FormData($(".attach_menu:eq("+index+")")[0]);

	var options={
    	type: "POST",
    	url: "attach_menu.php",
    	data: datastring,
    	dataType: "text",
    	async: false,
        success: function(data)
    	{
            console.log(data);
			location.reload();
        },
        error: function()
        {
            alert("An error has occured. Please try again.");
        },
        cache: false,
        contentType: false,
        processData: false
    };

	$.ajax(options);
}
