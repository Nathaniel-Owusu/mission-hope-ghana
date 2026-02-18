<?php
include 'auth_session.php';
include 'db.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_ministry'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $leader_name = $_POST['leader_name'];

    // Main Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $conn->prepare("INSERT INTO ministries (title, description, leader_name, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $leader_name, $image);
    $stmt->execute();
    header("Location: departments.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM ministries WHERE id=$id");
    header("Location: departments.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Departments</title>
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
            <h5 class="text-2xl font-serif font-bold text-brand-dark mb-6">Manage Departments</h5>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Add New Ministry</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" rows="4"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Leader Name</label>
                                <input type="text" name="leader_name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all">
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                                <input type="file" name="image" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-brand-light/10 file:text-brand-dark
                                  hover:file:bg-brand-light/20
                                " />
                            </div>
                            <button type="submit" name="add_ministry" class="w-full bg-brand-DEFAULT hover:bg-brand-dark text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                Add Ministry
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Current Ministries</h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="py-3 px-2 font-semibold">Image</th>
                                        <th class="py-3 px-2 font-semibold">Title</th>
                                        <th class="py-3 px-2 font-semibold">Leader</th>
                                        <th class="py-3 px-2 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $result = $conn->query("SELECT * FROM ministries");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='border-b last:border-0 hover:bg-gray-50 transition-colors'>";
                                            echo "<td class='py-3 px-2'>";
                                            if ($row['image']) {
                                                echo "<img src='../{$row['image']}' class='w-12 h-12 object-cover rounded-md shadow-sm'>";
                                            } else {
                                                echo "<div class='w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400'><ion-icon name='image-outline'></ion-icon></div>";
                                            }
                                            echo "</td>";
                                            echo "<td class='py-3 px-2 font-medium text-gray-900'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td class='py-3 px-2 text-gray-600'>" . htmlspecialchars($row['leader_name']) . "</td>";
                                            echo "<td class='py-3 px-2 text-right'>
                                                <a href='departments.php?delete={$row['id']}' class='bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold transition-colors' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='py-4 text-center text-gray-500 italic'>No ministries found.</td></tr>";
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