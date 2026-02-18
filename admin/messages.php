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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1b4d3e',
                            DEFAULT: '#2d6a52',
                            light: '#4a8c5a',
                            gold: '#d4a373',
                            cream: '#fcfbf7'
                        }
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased selection:bg-brand-gold selection:text-white">

    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 ml-64 p-8">
            <h5 class="text-2xl font-serif font-bold text-brand-dark mb-6">Messages</h5>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Inbox</h6>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="py-3 px-2 font-semibold">Date</th>
                                <th class="py-3 px-2 font-semibold">From</th>
                                <th class="py-3 px-2 font-semibold">Email</th>
                                <th class="py-3 px-2 font-semibold">Subject</th>
                                <th class="py-3 px-2 font-semibold">Message</th>
                                <th class="py-3 px-2 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php
                            $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='border-b last:border-0 hover:bg-gray-50 transition-colors'>";
                                    echo "<td class='py-3 px-2 text-gray-600 whitespace-nowrap'>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                    echo "<td class='py-3 px-2 font-bold text-gray-900'>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</td>";
                                    echo "<td class='py-3 px-2 text-brand-light font-medium'>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td class='py-3 px-2 text-gray-800 font-medium'>" . htmlspecialchars($row['subject']) . "</td>";
                                    echo "<td class='py-3 px-2 text-gray-600 max-w-xs truncate' title='" . htmlspecialchars($row['message']) . "'>" . htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : '') . "</td>";
                                    echo "<td class='py-3 px-2 text-right'>
                                        <a href='messages.php?delete={$row['id']}' class='bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold transition-colors' onclick='return confirm(\"Delete?\")'>Delete</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='py-4 text-center text-gray-500 italic'>No messages found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>