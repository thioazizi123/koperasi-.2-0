<!DOCTYPE html>
<html>

<head>
    <title>Test Report Filter</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #f5f5f5;
        }

        .status {
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
        }

        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        pre {
            background: white;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <h1>🧪 Report Filter Test</h1>

    <div class="info">
        <strong>Testing URL:</strong> <a href="http://127.0.0.1:8000/reports"
            target="_blank">http://127.0.0.1:8000/reports</a>
    </div>

    <?php
    $url = 'http://127.0.0.1:8000/reports';

    echo '<h2>Test 1: Load Report Page (No Filter)</h2>';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        echo '<div class="success">✅ SUCCESS - Page loaded (HTTP 200)</div>';
        if (strpos($response, 'Laporan Keuangan') !== false) {
            echo '<div class="success">✅ Title "Laporan Keuangan" found</div>';
        }
        if (strpos($response, 'Kode Transaksi') !== false) {
            echo '<div class="success">✅ Filter form "Kode Transaksi" found</div>';
        }
        if (strpos($response, 'KODE') !== false) {
            echo '<div class="success">✅ CODE column header found</div>';
        }
    } else {
        echo '<div class="error">❌ FAILED - HTTP ' . $httpCode . '</div>';
        if ($response) {
            echo '<pre>' . htmlspecialchars(substr($response, 0, 500)) . '</pre>';
        }
    }

    echo '<h2>Test 2: Filter by Code 001</h2>';
    $url2 = 'http://127.0.0.1:8000/reports?code=001';
    $ch2 = curl_init($url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
    $response2 = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    if ($httpCode2 == 200) {
        echo '<div class="success">✅ SUCCESS - Filter by code works (HTTP 200)</div>';
        if (strpos($response2, 'Simpanan Wajib') !== false) {
            echo '<div class="success">✅ Title shows "Simpanan Wajib"</div>';
        }
    } else {
        echo '<div class="error">❌ FAILED - HTTP ' . $httpCode2 . '</div>';
    }

    echo '<h2>Test 3: Filter by Year</h2>';
    $currentYear = date('Y');
    $url3 = 'http://127.0.0.1:8000/reports?year=' . $currentYear;
    $ch3 = curl_init($url3);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch3, CURLOPT_TIMEOUT, 10);
    $response3 = curl_exec($ch3);
    $httpCode3 = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
    curl_close($ch3);

    if ($httpCode3 == 200) {
        echo '<div class="success">✅ SUCCESS - Filter by year works (HTTP 200)</div>';
        if (strpos($response3, 'Tahun ' . $currentYear) !== false) {
            echo '<div class="success">✅ Title shows "Tahun ' . $currentYear . '"</div>';
        }
    } else {
        echo '<div class="error">❌ FAILED - HTTP ' . $httpCode3 . '</div>';
    }
    ?>

    <hr>
    <p><strong>Manual Test:</strong> Click the link above to test the filter manually in your browser.</p>
</body>

</html>