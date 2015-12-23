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
		document.getElementById('validated').style.display='block';
		document.getElementById('not_validated').style.display='none';
	}
	else
		if (var_show==0)
		{
			document.getElementById('validated').style.display='none';
			document.getElementById('not_validated').style.display='block';
		}
}
