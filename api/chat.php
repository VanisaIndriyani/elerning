<?php
// api/chat.php
header('Content-Type: application/json');
require_once '../config/ai_config.php';

// Allow CORS for public access if needed (though same origin is better)
// header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['error' => 'Pesan tidak boleh kosong']);
    exit;
}

if (empty(GEMINI_API_KEY) || GEMINI_API_KEY === 'YOUR_API_KEY_HERE') {
    echo json_encode(['error' => 'API Key belum dikonfigurasi. Silakan hubungi Admin.']);
    exit;
}

// Gemini API Endpoint (use latest for best compatibility)
function fetch_models($version) {
    $url = "https://generativelanguage.googleapis.com/{$version}/models?key=" . GEMINI_API_KEY;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }
    curl_close($ch);
    $data = json_decode($resp, true);
    return $data['models'] ?? null;
}

function pick_model($models) {
    if (!$models) return null;
    foreach ($models as $m) {
        $name = $m['name'] ?? '';
        $methods = $m['supportedGenerationMethods'] ?? $m['supported_generation_methods'] ?? [];
        if (in_array('generateContent', $methods) && strpos($name, 'gemini-1.5-flash') !== false) {
            return $name;
        }
    }
    foreach ($models as $m) {
        $name = $m['name'] ?? '';
        $methods = $m['supportedGenerationMethods'] ?? $m['supported_generation_methods'] ?? [];
        if (in_array('generateContent', $methods)) {
            return $name;
        }
    }
    return null;
}

$versions = ['v1', 'v1beta'];
$chosenVersion = null;
$chosenModel = null;

foreach ($versions as $v) {
    $models = fetch_models($v);
    $modelName = pick_model($models);
    if ($modelName) {
        $chosenVersion = $v;
        $chosenModel = $modelName;
        break;
    }
}

if (!$chosenModel) {
    echo json_encode(['error' => 'Tidak menemukan model yang mendukung generateContent untuk API key ini.']);
    exit;
}

// Normalisasi nama model dari ListModels:
// API ListModels biasanya mengembalikan 'name' seperti 'models/gemini-1.5-flash' atau 'models/gemini-1.5-flash-latest'
// Endpoint generateContent mengharapkan path 'models/<modelName>'
$modelPath = $chosenModel;
if (substr($modelPath, 0, 7) !== 'models/') {
    $modelPath = 'models/' . $modelPath;
}

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => $userMessage]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "maxOutputTokens" => 1024
    ]
];

$url = "https://generativelanguage.googleapis.com/{$chosenVersion}/{$modelPath}:generateContent?key=" . GEMINI_API_KEY;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);
$responseData = json_decode($response, true);
if (isset($responseData['error'])) {
    $msg = $responseData['error']['message'] ?? 'Unknown API error';
    $code = $responseData['error']['code'] ?? 0;
    echo json_encode(['error' => "API error ($code): $msg", 'details' => $responseData]);
    exit;
}
if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(['reply' => $responseData['candidates'][0]['content']['parts'][0]['text']]);
    exit;
}
$finishReason = $responseData['candidates'][0]['finishReason'] ?? '';
if ($finishReason === 'SAFETY') {
    echo json_encode(['error' => 'Konten diblokir oleh kebijakan AI (SAFETY). Coba ubah pertanyaan.']);
    exit;
}
echo json_encode(['error' => 'Gagal mendapatkan respons dari AI.', 'details' => $responseData]);
?>
