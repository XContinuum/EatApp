function validate(var_id)
{
    $.get("manipulate_data.php?id="+var_id+"&type=validate");
    return false;
}

function unvalidate(var_id)
{
    $.get("manipulate_data.php?id="+var_id+"&type=unvalidate");
    return false;
}


function ban(var_id)
{
    $.get("manipulate_data.php?id="+var_id+"&type=ban");
    return false;
}


function show(var_show)
{
	if (var_show==1)
	{
		$('#validated').show();
		$('#not_validated').hide();
	}
	else
		if (var_show==0)
		{
			$('#validated').hide();
			$('#not_validated').show();
		}
}


// $(document).ready(function()
// {

// });
