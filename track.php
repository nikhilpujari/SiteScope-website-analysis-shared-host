<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['url']) || !isset($input['ua'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input"]);
        exit();
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    $url = $input['url'];
    $referrer = $input['referrer'] ?? '';
    $ua = $input['ua'];
    $screen = $input['screen'] ?? '';
    $hashed_id = hash('sha256', $ip . $ua);

    $country = "Unknown";
    $city = "Unknown";

    // Try to fetch geolocation
    // First, try with ip-api.com (free for non-commercial use)
    $geoResponse = @file_get_contents("http://ip-api.com/json/{$ip}");
    if ($geoResponse !== false) {
        $geoData = json_decode($geoResponse, true);
        if ($geoData && $geoData['status'] === 'success') {
            $country = $geoData['country'];
            $city = $geoData['city'];
        }
    }
    
    // If ip-api.com fails, try with geoiplookup.io as a fallback
    if ($country === "Unknown") {
        $geoResponse = @file_get_contents("https://api.geoiplookup.io/?ip={$ip}");
        if ($geoResponse !== false) {
            $geoData = json_decode($geoResponse, true);
            if (isset($geoData['country_name'])) {
                $country = $geoData['country_name'];
                $city = $geoData['city'] ?? 'Unknown';
            }
        }
    }
    
    // As a last resort, try the original ipapi.co
    if ($country === "Unknown") {
        $geoResponse = @file_get_contents("https://ipapi.co/{$ip}/json/");
        if ($geoResponse !== false) {
            $geoData = json_decode($geoResponse, true);
            if (isset($geoData['country_name']) && !isset($geoData['error'])) {
                $country = $geoData['country_name'];
                $city = $geoData['city'] ?? 'Unknown';
            }
        }
    }

    // Save to SQLite
    $db = new SQLite3('analytics.db');
    $db->exec("CREATE TABLE IF NOT EXISTS visits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        hashed_id TEXT,
        ip TEXT,
        country TEXT,
        city TEXT,
        url TEXT,
        referrer TEXT,
        user_agent TEXT,
        screen TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare("INSERT INTO visits (hashed_id, ip, country, city, url, referrer, user_agent, screen)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $hashed_id, SQLITE3_TEXT);
    $stmt->bindValue(2, $ip, SQLITE3_TEXT);
    $stmt->bindValue(3, $country, SQLITE3_TEXT);
    $stmt->bindValue(4, $city, SQLITE3_TEXT);
    $stmt->bindValue(5, $url, SQLITE3_TEXT);
    $stmt->bindValue(6, $referrer, SQLITE3_TEXT);
    $stmt->bindValue(7, $ua, SQLITE3_TEXT);
    $stmt->bindValue(8, $screen, SQLITE3_TEXT);
    $stmt->execute();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "geo_status" => ($country !== "Unknown") ? "success" : "failed",
        "country" => $country,
        "city" => $city
    ]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>