<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = isset($_POST['name']) ? $_POST['name'] : 'No name';
    $email = isset($_POST['email']) ? $_POST['email'] : 'No email';
    $message = isset($_POST['message']) ? $_POST['message'] : 'No message';
    
    if (isset($_FILES['file'])) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];

        if ($fileError === 0) {
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedTypes = ["jpg", "png", "jpeg", "pdf"];
            if (in_array(strtolower($fileExt), $allowedTypes)) {
                $uploadDir = "uploads/";
                $uploadFilePath = $uploadDir . basename($fileName);
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    $fileUploaded = true;
                } else {
                    $fileUploaded = false;
                }
            } else {
                die("Invalid file type. Allowed types are: JPG, PNG, JPEG, PDF.");
            }
        } else {
            die("Error uploading file.");
        }
    } else {
        $fileUploaded = false;
    }

    $to = "brakob2001@gmail.com";
    $subject = "New Message from $name";

    $emailBody = "You have received a new message:\n\n";
    $emailBody .= "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Message: $message\n";

    if ($fileUploaded) {
        $emailBody .= "\nFile uploaded: $fileName\n";
    } else {
        $emailBody .= "\nNo file uploaded.\n";
    }

    $headers = "From: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $emailBody, $headers)) {
        header("Location: thankyou.html");
        exit();
    } else {
        echo "There was a problem sending your message.";
    }
} else {
    echo "Invalid request method.";
}
?>