<?php
header('Content-Type: application/json');

// Helper function to return JSON response
function jsonResponse($success, $message, $file_url = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'file_url' => $file_url
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
    jsonResponse(false, 'Please upload a valid PDF file.');
}

$file = $_FILES['pdf_file'];

// Validate file type
$fileType = mime_content_type($file['tmp_name']);
if ($fileType !== 'application/pdf') {
    jsonResponse(false, 'Only PDF files are allowed.');
}

$uploadsDir = __DIR__ . '/uploads/';
$outputsDir = __DIR__ . '/outputs/';

// Ensure directories exist
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
if (!is_dir($outputsDir)) mkdir($outputsDir, 0777, true);

// Generate unique filenames
$filenameBase = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($file['name'], '.pdf'));
$pdfPath = $uploadsDir . $filenameBase . '.pdf';
$xlsxPath = $outputsDir . $filenameBase . '.xlsx';

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $pdfPath)) {
    jsonResponse(false, 'Failed to save uploaded file.');
}

// Define the exact path to Python executable
// From the Laragon environment:
$pythonExe = 'C:\\laragon\\bin\\python\\python-3.13\\python.exe';

// Escape paths for safe command line execution
$escapedPdfPath = escapeshellarg($pdfPath);
$escapedXlsxPath = escapeshellarg($xlsxPath);
$escapedConverterPath = escapeshellarg(__DIR__ . '/converter.py');

// Build command
$command = "\"$pythonExe\" $escapedConverterPath $escapedPdfPath $escapedXlsxPath 2>&1";

// Execute the Python script
$output = [];
$return_var = 0;
exec($command, $output, $return_var);

// Check if successful
if ($return_var === 0 && file_exists($xlsxPath)) {
    // Generate public URL for the output file
    // Assuming the app is accessed via localhost/pdf to xls/
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $fileUrl = "http://$host$uri/outputs/" . $filenameBase . '.xlsx';
    
    jsonResponse(true, 'Conversion successful.', $fileUrl);
} else {
    // Collect output for debugging
    $errorDetails = implode(" ", $output);
    jsonResponse(false, 'Conversion failed. Python error: ' . $errorDetails);
}
?>
