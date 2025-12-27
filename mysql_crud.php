<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'siva_employee_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action) {
    case 'read':
        $stmt = $pdo->query("SELECT * FROM Employee ORDER BY empno");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'create':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO Employee VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['createId'], $data['createName'], $data['createSalary'], 
                       $data['createDate'], $data['createGender'], $data['createEmail']]);
        echo json_encode(['success' => true]);
        break;
        
    case 'update':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE Employee SET salary = ? WHERE empno = ?");
        $stmt->execute([$data['salary'], $data['empno']]);
        echo json_encode(['success' => true]);
        break;
        
    case 'delete':
        $empno = $_GET['empno'];
        $stmt = $pdo->prepare("DELETE FROM Employee WHERE empno = ?");
        $stmt->execute([$empno]);
        echo json_encode(['success' => true]);
        break;
}
?>
