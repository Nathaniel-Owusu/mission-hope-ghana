<?php
include 'auth_session.php';
include 'db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM messages WHERE id=$id");
    header("Location: messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Messages</h5>
        </div>

        <div class="container-fluid mt-4">
             <div class="card p-3">
                <h6>Inbox</h6>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>From</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
                        while($row = $result->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>{$row['created_at']}</td>";
                            echo "<td><strong>{$row['first_name']} {$row['last_name']}</strong></td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['subject']}</td>";
                            echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                            echo "<td>
                                <a href='messages.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Delete?\")'>X</a>
                            </td>";
                            echo "</tr>";
                        }
                        if($result->num_rows == 0) echo "<tr><td colspan='6' class='text-center'>No messages found.</td></tr>";
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
