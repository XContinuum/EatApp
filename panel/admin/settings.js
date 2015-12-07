function validate(var_id)
{
    $.get("manipulate_data.php?id="+var_id+"&type=validate");
    return false;
}

function ban(var_id)
{
    $.get("manipulate_data.php?id="+var_id+"&type=ban");
    return false;
}
