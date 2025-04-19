<?php
header('Content-Type: application/json');
$db = new SQLite3('analytics.db');

$total_views = $db->querySingle("SELECT COUNT(*) FROM visits");
$unique_visitors = $db->querySingle("SELECT COUNT(DISTINCT hashed_id) FROM visits");

$top_pages_result = $db->query("SELECT url, COUNT(*) as views FROM visits GROUP BY url ORDER BY views DESC LIMIT 5");
$top_pages = [];
while ($row = $top_pages_result->fetchArray(SQLITE3_ASSOC)) {
    $top_pages[] = $row;
}

// Get top referrers (optional: only if referrer is not empty)
$referrers_result = $db->query("SELECT referrer, COUNT(*) as count FROM visits WHERE referrer != '' GROUP BY referrer ORDER BY count DESC LIMIT 5");
$referrers = [];
while ($row = $referrers_result->fetchArray(SQLITE3_ASSOC)) {
    $referrers[] = $row;
}

// ✅ Get top locations
$locations_result = $db->query("
  SELECT country, city, COUNT(*) as views
  FROM visits
  WHERE country != 'Unknown' AND city != 'Unknown'
  GROUP BY country, city
  ORDER BY views DESC
  LIMIT 5
");

$top_locations = [];
while ($row = $locations_result->fetchArray(SQLITE3_ASSOC)) {
    $top_locations[] = $row;
}


echo json_encode([
    'total_views' => $total_views,
    'unique_visitors' => $unique_visitors,
    'top_pages' => $top_pages,
    'referrers' => $referrers,
    'top_locations' => $top_locations
]);
?>
