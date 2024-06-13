<?php
// Turn on output buffering
ob_start();

// Output some content
echo "Hello, ";

// Store some content in a variable
$content = "World!";

// Output the stored content
echo $content;

// Get the current buffer contents and delete current output buffer
$output = ob_get_clean();

// Output the final content
echo $output;
?>