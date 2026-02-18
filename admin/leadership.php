<?php
include 'auth_session.php';
include 'db.php';

// Handle Add Position
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_position'])) {
    $pos_name = trim($_POST['position_name']);
    if (!empty($pos_name)) {
        $stmt = $conn->prepare("INSERT INTO positions (name) VALUES (?)");
        $stmt->bind_param("s", $pos_name);
        $stmt->execute();
    }
    header("Location: leadership.php");
    exit();
}

// Handle Delete Position
if (isset($_GET['delete_pos'])) {
    $id = $_GET['delete_pos'];
    $conn->query("DELETE FROM positions WHERE id=$id");
    header("Location: leadership.php");
    exit();
}

// Handle Add Leader
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_leader'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $category = $_POST['category']; // This is now the Position Name
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../"; // Upload to root
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $conn->prepare("INSERT INTO leadership (name, role, category, email, phone, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $role, $category, $email, $phone, $image);
    $stmt->execute();
    header("Location: leadership.php");
    exit();
}

// Handle Delete Leader
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM leadership WHERE id=$id");
    header("Location: leadership.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Leadership</title>
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
            <h5 class="text-2xl font-serif font-bold text-brand-dark mb-6">Manage Leadership</h5>

            <!-- Manage Positions Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h6 class="font-bold text-lg text-gray-800">Manage Positions</h6>
                    <form method="POST" class="flex gap-2">
                        <input type="text" name="position_name" class="px-3 py-1 text-sm rounded-lg border border-gray-300 focus:border-brand-gold outline-none" placeholder="New Position Name" required>
                        <button type="submit" name="add_position" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm font-semibold transition-colors">+ Add Position</button>
                    </form>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $pos_res = $conn->query("SELECT * FROM positions ORDER BY name ASC");
                    if ($pos_res->num_rows > 0) {
                        while ($pos = $pos_res->fetch_assoc()) {
                            echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-2 border border-gray-200">
                                    ' . htmlspecialchars($pos['name']) . '
                                    <a href="leadership.php?delete_pos=' . $pos['id'] . '" class="text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full w-4 h-4 flex items-center justify-center text-xs leading-none shadow-sm" onclick="return confirm(\'Delete this position?\')">&times;</a>
                                  </span>';
                        }
                    } else {
                        echo '<span class="text-gray-400 text-sm italic">No positions added yet.</span>';
                    }
                    ?>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Leader Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Add New Leader</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role Description</label>
                                <input type="text" name="role" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" placeholder="e.g. Head Elder, Senior Pastor" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position (Category)</label>
                                <select name="category" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all bg-white" required>
                                    <option value="">Select Position...</option>
                                    <?php
                                    // Reset pointer
                                    if (isset($pos_res)) {
                                        $pos_res->data_seek(0);
                                        while ($pos = $pos_res->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($pos['name']) . '">' . htmlspecialchars($pos['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" name="email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" name="phone" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all">
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                <input type="file" name="image" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-brand-light/10 file:text-brand-dark
                                  hover:file:bg-brand-light/20
                                " />
                            </div>
                            <button type="submit" name="add_leader" class="w-full bg-brand-DEFAULT hover:bg-brand-dark text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                Add Leader
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Current Leadership</h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="py-3 px-2 font-semibold">Image</th>
                                        <th class="py-3 px-2 font-semibold">Name</th>
                                        <th class="py-3 px-2 font-semibold">Role</th>
                                        <th class="py-3 px-2 font-semibold">Position</th>
                                        <th class="py-3 px-2 font-semibold text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $result = $conn->query("SELECT * FROM leadership ORDER BY id DESC");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='border-b last:border-0 hover:bg-gray-50 transition-colors'>";
                                            echo "<td class='py-3 px-2'>";
                                            if ($row['image']) {
                                                echo "<img src='../{$row['image']}' class='w-10 h-10 object-cover rounded-full border border-gray-200 shadow-sm'>";
                                            } else {
                                                echo "<div class='w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400'><ion-icon name='person-outline'></ion-icon></div>";
                                            }
                                            echo "</td>";
                                            echo "<td class='py-3 px-2 font-medium text-gray-900'>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td class='py-3 px-2 text-gray-600'>" . htmlspecialchars($row['role']) . "</td>";
                                            echo "<td class='py-3 px-2'><span class='bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-semibold'>" . htmlspecialchars($row['category']) . "</span></td>";
                                            echo "<td class='py-3 px-2 text-right'>
                                                <a href='leadership.php?delete={$row['id']}' class='bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold transition-colors' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='py-4 text-center text-gray-500 italic'>No leaders found.</td></tr>";
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