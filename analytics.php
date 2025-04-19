<?php
header('Content-Type: application/json');
$db = new SQLite3('analytics.db');

$result = $db->query("SELECT * FROM visits ORDER BY timestamp DESC");
$rows = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $rows[] = $row;
}
echo json_encode($rows);
?>
