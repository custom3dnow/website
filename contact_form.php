<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $attachments = $_FILES['picture'];

    // Prepare email message
    $to = 'szrspot@gmail.com';
    $subject = 'Contact Form Submission';
    $message_body = "Name: $name\n";
    $message_body .= "Company: $company\n";
    $message_body .= "Email: $email\n";
    $message_body .= "Phone: $phone\n";
    $message_body .= "Message:\n$message\n";

    // Send email
    $headers = "From: $email";
    mail($to, $subject, $message_body, $headers);

    // Handle attachments
    foreach ($attachments['tmp_name'] as $index => $tmp_name) {
        $file_name = $attachments['name'][$index];
        $file_tmp = $attachments['tmp_name'][$index];
        $file_type = $attachments['type'][$index];
        $file_error = $attachments['error'][$index];
        $file_size = $attachments['size'][$index];
        
        if ($file_error == UPLOAD_ERR_OK && $file_size > 0) {
            $file_path = "uploads/" . $file_name;
            move_uploaded_file($file_tmp, $file_path);
        }
    }
}
?>
