<?php
$secret = __DIR__ . '/ai_secret.php';
if (file_exists($secret)) {
    require $secret;
} else {
    define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
}
?>
