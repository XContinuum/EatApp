$(document).ready(function()
{
    var x_timer;

    $("#check_email").keyup(function (e)
    {
        clearTimeout(x_timer);
        var email = $(this).val();
        x_timer = setTimeout(function(){check_email_ajax(email);}, 1000);
    });
});
/*

    ONLOAD EVENT+++++++++

*/

/* check email */
function send_email(email)
{
    var xhttp;
    if (window.XMLHttpRequest)
    {
        xhttp = new XMLHttpRequest();
    }
    else
        {
            //code for IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

    xhttp.onreadystatechange = function()
    {
        if (xhttp.readyState == 4 && xhttp.status == 200)
        {
        document.getElementById("demo").innerHTML = xhttp.responseText;
        }
    };

    xmlhttp.open("POST", "check_email.php", true);
    xmlhttp.send();
}
