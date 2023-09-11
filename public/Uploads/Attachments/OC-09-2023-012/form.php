<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$from_email = $_POST["email"]; // from mail, sender email address
    $recipient_email = 'tabwritingservice@gmail.com'; // recipient email address

    $sender_name = $_POST["name"]; // sender name
    $reply_to_email = $_POST["email"]; // sender email, it will be used in "reply-to" header
    $subject = "Write My Resume"; // subject for the email
    $message = $_POST["message"]; // body of the email

    $tmp_name = $_FILES['attachment']['tmp_name']; // get the temporary file name of the file on the server
    $name = $_FILES['attachment']['name']; // get the name of the file
    $size = $_FILES['attachment']['size']; // get size of the file for size validation
    $type = $_FILES['attachment']['type']; // get type of the file
    $error = $_FILES['attachment']['error']; // get the error (if any)

    // validate form field for attaching the file
    if ($error > 0) {
        // No files uploaded or upload error
        $sentMailResult = mail($recipient_email, $subject, $message, "From: $from_email\r\nReply-To: $reply_to_email\r\n");
    } else {
        $handle = fopen($tmp_name, "r"); // set the file handle only for reading the file
        $content = fread($handle, $size); // reading the file
        fclose($handle); // close upon completion

        $encoded_content = chunk_split(base64_encode($content));
        $boundary = md5("random"); // define boundary with an md5 hashed value

        // header
        $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
        $headers .= "From:" . $from_email . "\r\n"; // Sender Email
        $headers .= "Reply-To: " . $reply_to_email . "\r\n"; // Email address to reach back
        $headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
        $headers .= "boundary = $boundary\r\n"; // Defining the Boundary

        // plain text
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));

        // attachment
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $type; name=" . $name . "\r\n";
        $body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= $encoded_content; // Attaching the encoded file with email

        $sentMailResult = mail($recipient_email, $subject, $body, $headers);
    }

    if ($sentMailResult) {

            header("location: index.php");
        
    }
}