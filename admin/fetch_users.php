<?php
require "../config/db.php";

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$date   = $_GET['date'] ?? '';

$sql = "
SELECT 
    u.id,
    u.fullname,
    u.email,
    m.plan_name,
    tb.trainer_name,
    tb.status,
    tb.booking_date
FROM users u
LEFT JOIN memberships m ON u.id = m.user_id
LEFT JOIN trainer_bookings tb ON u.id = tb.user_id
WHERE 
    u.fullname LIKE '%$search%' OR 
    u.email LIKE '%$search%'
";

if (!empty($status)) {
    $sql .= " AND tb.status = '$status'";
}

if (!empty($date)) {
    $sql .= " AND DATE(tb.booking_date) = '$date'";
}

$sql .= " ORDER BY u.id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()):
?>

<tr>
  <td>#<?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['fullname']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= $row['plan_name'] ?? "No Plan" ?></td>
  <td><?= $row['trainer_name'] ?? "Not Booked" ?></td>

  <td>
    <span class="status <?= $row['status'] == 'booked' ? 'active' : 'pending' ?>">
      <?= ucfirst($row['status'] ?? 'Pending') ?>
    </span>
  </td>

  <!-- ✅ ACTION COLUMN -->
  <td>
    <a href="edit_user.php?id=<?= $row['id'] ?>" class="icon-btn edit">✏️</a>

    <a href="delete_user.php?id=<?= $row['id'] ?>" 
       class="icon-btn delete"
       onclick="return confirm('Delete user?')">🗑</a>
  </td>
</tr>

<?php endwhile; ?>

