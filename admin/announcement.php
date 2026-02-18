<?php
include 'auth_session.php';
include 'db.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $message);
    $stmt->execute();
    header("Location: announcement.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM announcements WHERE id=$id");
    header("Location: announcement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Announcements</title>
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
            <div class="flex justify-between items-center mb-8">
                <h5 class="text-xl font-bold text-brand-dark font-serif">Manage Announcements</h5>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <h6 class="font-bold text-lg mb-4 text-brand-dark border-b border-gray-100 pb-2">Add New Announcement</h6>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea name="message" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" rows="4" required></textarea>
                            </div>
                            <button type="submit" name="add_announcement" class="w-full bg-brand-DEFAULT hover:bg-brand-dark text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-sm">
                                Post Announcement
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h6 class="font-bold text-lg mb-4 text-brand-dark border-b border-gray-100 pb-2">Current Announcements</h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="py-3 font-semibold">Date</th>
                                        <th class="py-3 font-semibold">Title</th>
                                        <th class="py-3 font-semibold">Message</th>
                                        <th class="py-3 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='border-b last:border-0 border-gray-50 hover:bg-gray-50 transition-colors'>";
                                            echo "<td class='py-3 text-gray-600 whitespace-nowrap'>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                            echo "<td class='py-3 font-medium text-brand-dark'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td class='py-3 text-gray-600'>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                                            echo "<td class='py-3 text-right'>
                                                <a href='announcement.php?delete={$row['id']}' class='bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 px-3 py-1 rounded-md text-xs font-semibold transition-colors' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='py-4 text-center text-gray-500 italic'>No announcements found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>