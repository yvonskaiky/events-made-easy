<?php
// phpmailer support
function eme_send_mail($subject="no title",$body="No message specified", $receiveremail, $receivername='', $replytoemail='', $replytoname='') {

   require_once(ABSPATH . WPINC . "/class-phpmailer.php");
   // there's a bug in class-phpmailer from wordpress, so we need to copy class-smtp.php
   // in this dir for smtp to work
      
   if (class_exists('PHPMailer')) {
      $mail = new PHPMailer();
      $mail->ClearAllRecipients();
      $mail->ClearAddresses();
      $mail->ClearAttachments();
      $mail->CharSet = 'utf-8';
      $mail->SetLanguage('en', dirname(__FILE__).'/');

      $mail->PluginDir = dirname(__FILE__).'/';
      get_option('eme_rsvp_mail_send_method') == 'qmail' ?
         $mail->IsQmail() :
         $mail->Mailer = get_option('eme_rsvp_mail_send_method');
      $mail->Host = get_option('eme_smtp_host');
      $mail->port = get_option('eme_rsvp_mail_port');
      if(get_option('eme_rsvp_mail_SMTPAuth') == '1')
         $mail->SMTPAuth = TRUE;
      $mail->Username = get_option('eme_smtp_username');
      $mail->Password = get_option('eme_smtp_password');
      if (get_option('eme_mail_sender_address') == "") {
         $mail->From = $replytoemail;
         $mail->FromName = $replytoname;
      } else {
         $mail->From = get_option('eme_mail_sender_address');
         $mail->FromName = get_option('eme_mail_sender_name'); // This is the from name in the email, you can put anything you like here
      }
      $mail->Body = $body;
      $mail->Subject = $subject;
      if ($replytoemail != "")
         $mail->AddReplyTo($replytoemail,$replytoname);

      if ($receiveremail != "") {
         $mail->AddAddress($receiveremail,$receivername);
         if (get_option('eme_smtp_debug'))
            $mail->SMTPDebug = true;
         if(!$mail->Send()){
            #echo "<br />Message was not sent<br/ >";
            #echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
         } else {
            return true;
         }
      } else {
         return false;
      }
   }
}
?>
