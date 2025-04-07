<?php
// upload_handler.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $name = htmlspecialchars($_POST['name']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    $contactMethod = $_POST['contactMethod'];
    
    // File upload handling
    $uploadStatus = '';
    $uploadedFileUrl = '';
    
    if (isset($_FILES['documentUpload']) && $_FILES['documentUpload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $maxFileSize = 100 * 1024 * 1024; // 100MB
        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '', $_FILES['documentUpload']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if ($_FILES['documentUpload']['size'] <= $maxFileSize) {
            if (move_uploaded_file($_FILES['documentUpload']['tmp_name'], $targetPath)) {
                $uploadedFileUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . 
                                  $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $targetPath;
                $uploadStatus = 'File uploaded successfully!';
            }
        }
    }
    
    // Prepare the message content
    $fullMessage = "New Contact Request\n\n";
    $fullMessage .= "Name: $name\n";
    $fullMessage .= "Subject: $subject\n";
    $fullMessage .= "Message: $message\n";
    
    if (!empty($uploadedFileUrl)) {
        $fullMessage .= "\nDocument Link: $uploadedFileUrl";
    }
    
    // Handle the contact method
    if ($contactMethod === 'whatsapp') {
        // Redirect to WhatsApp with the message and file link
        $whatsappUrl = "https://wa.me/12815070243?text=" . urlencode($fullMessage);
        header("Location: $whatsappUrl");
        exit();
    } elseif ($contactMethod === 'email') {
        // Send email with attachment
        $to = "virtualacademia01@gmail.com";
        $emailSubject = "New Contact Request: $subject";
        $headers = "From: $name <noreply@yourdomain.com>\r\n";
        $headers .= "Reply-To: $name <noreply@yourdomain.com>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        
        if (!empty($uploadedFileUrl)) {
            // Email with attachment
            $boundary = md5(time());
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
            
            $body = "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $fullMessage . "\r\n\r\n";
            
            // Attach file
            $fileContent = file_get_contents($targetPath);
            $fileContent = chunk_split(base64_encode($fileContent));
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $fileContent . "\r\n\r\n";
            $body .= "--$boundary--";
        } else {
            // Plain text email
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $body = $fullMessage;
        }
        
        if (mail($to, $emailSubject, $body, $headers)) {
            header("Location: thank_you.html");
        } else {
            header("Location: error.html");
        }
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: index.html");
    exit();
}
?>
