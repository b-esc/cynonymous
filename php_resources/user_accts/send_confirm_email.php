<?PHP
/**
 * Sends a confirmation email to a user.
 *
 * @param string $email The email address to send the email to.
 * @param string $verification_code The verification code to send with the email.
 * @param string $username The username of the user in question.
 * @return void
 */
    function send_email($email,$verification_code,$username){
        $subject = "Confirm your account!";
        $message = "follow this link: http://proj-309-vc-7.cs.iastate.edu/php_resources/user_accts/confirm_account.php?uname=$username&ver_code=$verification_code";
        $header = "From: CyNonymous <chis@proj-309-vc-7.cs.iastate.edu>";
        mail($email, $subject, $message, $header);
    }
?>
