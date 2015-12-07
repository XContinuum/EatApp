function resendEmailConfirmation()
{
    $.get("../requests/resend_email.php");
    return false;
}
