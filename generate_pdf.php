<?php
require_once('tcpdf/library/tcpdf.php');

@include 'db_config.php';

function displayImageFromBlob($pdf, $imageData, $x, $y, $width, $height) {
    if ($imageData) {
        $imageData = base64_encode($imageData);
        $imagePath = tempnam(sys_get_temp_dir(), 'user_image_');

        // Save the image to a temporary file
        file_put_contents($imagePath, base64_decode($imageData));

        // Output the image using the Image method
        $pdf->Image($imagePath, $x, $y, $width, $height);

        // Remove the temporary file
        unlink($imagePath);
    }
}

// Fetch data based on the email passed through the query parameter
$email = $_GET['email'];
$sql = "SELECT name_last, name_first, name_middle, name_suffix, birthday, sex, civilstatus, cpnum, birthplace, address_no, address_street, address_village, barangay, city, zipcode, email, pass, valid_id, two_x_two_id, date_registered FROM voter_registration WHERE email = '$email'";
/*
$sql = "SELECT full_name, email, date_registered, email, birthday, sex, cp_number, full_address, valid_id , 1x1_picture FROM voter_registration WHERE email = '$email'"; 
*/
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    // Create a new TCPDF instance
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('User Information PDF');
    $pdf->SetMargins(0, 0, 0, true);
    $pdf->SetFont('helvetica', '', 11); // Font family, style (empty for regular), and size

    // Add a page
    $pdf->AddPage('P','A4');
    // Get the available width and height for the content
    $contentWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
    $contentHeight = $pdf->getPageHeight() - $pdf->getMargins()['top'] - $pdf->getMargins()['bottom'];

    // Add the image as the background
    $imageFile = 'img/Votify Voters Information.jpg';

    // Calculate the image height to reach the edge of the page
    $imageHeight = $contentHeight;

    $pdf->Image($imageFile, 0, 0, $contentWidth, $imageHeight, '', '', '', false, 350, '', false, false, 0);
    
    //voter contents
    displayImageFromBlob($pdf, $row['two_x_two_id'], 111, 40, 31, 31); // 1x1 picture
    displayImageFromBlob($pdf, $row['valid_id'], 153, 40, 49, 31); // 1x1 picture

    
    $pdf->Text(16, 68, $row['date_registered']);
    $pdf->Text(16, 82, $row['name_last']);
    $pdf->Text(70, 82, $row['name_first']);
    $pdf->Text(127, 82, $row['name_middle']);
    $pdf->Text(182, 82, $row['name_suffix']);
    
    $pdf->Text(16, 96, $row['birthday']);
    $pdf->Text(70, 96, $row['birthplace']);
    $pdf->Text(127, 96, $row['civilstatus']);
    $pdf->Text(182, 96, $row['sex']);
    
    $pdf->Text(16, 126, $row['address_no']);
    $pdf->Text(51, 126, $row['address_street']);
    $pdf->Text(116, 126, $row['address_village']);
    
    $pdf->Text(16, 140, $row['zipcode']);
    $pdf->Text(51, 140, $row['barangay']);
    $pdf->Text(116, 140, $row['city']);

    $pdf->Text(16, 154, $row['email']);


    /*
    // Output user information to the PDF
    $pdf->Cell(0, 10, 'Registration Details', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Full Name: ' . $row['name_last'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $row['email'], 0, 1);
    $pdf->Cell(0, 10, 'Sex: ' . $row['sex'], 0, 1);
    $pdf->Cell(0, 10, 'Birthday: ' . $row['birthday'], 0, 1);
    $pdf->Cell(0, 10, 'Contact Number: ' . $row['cpnum'], 0, 1);
    $pdf->Cell(0, 10, 'Full Address: ' . $row['address_no'], 0, 1);
    $pdf->Cell(0, 10, 'Date Registered: ' . $row['date_registered'], 0, 1);

    // Output the images
    displayImageFromBlob($pdf, $row['two_x_two_id'], 155, 21, 40, 40); // 1x1 picture
    $pdf->Cell(0, 20, 'Valid ID', 0, 1);
    displayImageFromBlob($pdf, $row['valid_id'], 11, 110, 40, 40); // Valid ID
    */

    // Output the PDF to the browser
    $pdf->Output('user_info.pdf', 'I');
} else {
    echo 'User not found';
}
?>
