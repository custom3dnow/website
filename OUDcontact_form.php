<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $attachments = $_FILES['picture'];

    // Prepare email message
    $to = 'sztspot@gmail.com';
    $subject = 'Contact Form Submission';
    $message_body = "Name: $name\n";
    $message_body .= "Company: $company\n";
    $message_body .= "Email: $email\n";
    $message_body .= "Phone: $phone\n";
    $message_body .= "Message:\n$message\n";

    // Email headers
    $headers = "From: $email";
    
    // Handle attachments
    $file_paths = [];
    foreach ($attachments['tmp_name'] as $index => $tmp_name) {
        $file_name = $attachments['name'][$index];
        $file_tmp = $attachments['tmp_name'][$index];
        $file_error = $attachments['error'][$index];
        $file_size = $attachments['size'][$index];
        
        if ($file_error == UPLOAD_ERR_OK && $file_size > 0) {
            $file_path = "uploads/" . basename($file_name);
            if (move_uploaded_file($file_tmp, $file_path)) {
                $file_paths[] = $file_path;
            }
        }
    }

    // Check if there are files to attach
    if (!empty($file_paths)) {
        $boundary = md5("sanwebe");
        $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary = $boundary\r\n\r\n";
        
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
        $body .= chunk_split(base64_encode($message_body));
        
        foreach ($file_paths as $file_path) {
            if (file_exists($file_path)) {
                $body .= "--$boundary\r\n";
                $body .= "Content-Type: application/octet-stream; name=" . basename($file_path) . "\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=" . basename($file_path) . "\r\n\r\n";
                $body .= chunk_split(base64_encode(file_get_contents($file_path)));
            }
        }
        
        $body .= "--$boundary--";
        mail($to, $subject, $body, $headers);
    } else {
        mail($to, $subject, $message_body, $headers);
    }
    
    // Redirect to a thank you page or display a success message
    echo "Thank you for contacting us. We will get back to you shortly.";
}
?>
