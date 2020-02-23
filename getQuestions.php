<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = "mysql:host=localhost;dbname=comp424db";

try {
  $db = new PDO($conn, "root", "root", [
    PDO::ATTR_PERSISTENT => true
  ]); // use the proper root credentials
}
catch(PDOException $e) {
  die("Could not connect: " . $e->getMessage());
}

$stmt = $db->prepare("SELECT * from questions");
$stmt->execute([0]);

$results = $stmt->fetchAll();

$data = [];
foreach($results as $result)
{
    $data[] = $result["question"];
}

echo json_encode($data);

?>