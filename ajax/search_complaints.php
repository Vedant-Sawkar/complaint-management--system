<?php
include "../includes/connection.php";

$search = $_GET['search'] ?? '';
// Add wildcard characters securely outside the SQL query
$search_param = "%" . $search . "%";

// Secure SELECT query using Prepared Statement
$sql = "SELECT complaints.*, users.name 
        FROM complaints 
        INNER JOIN users 
        ON complaints.user_id = users.id 
        WHERE complaints.title LIKE ? 
        OR users.name LIKE ? 
        ORDER BY complaints.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
?>
<tr>
    <!-- Output sanitization using htmlspecialchars to prevent XSS -->
    <td><?php echo htmlspecialchars($row['id']); ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo htmlspecialchars($row['priority']); ?></td>
    <td><?php echo htmlspecialchars($row['status']); ?></td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
</tr>
<?php
}
?>