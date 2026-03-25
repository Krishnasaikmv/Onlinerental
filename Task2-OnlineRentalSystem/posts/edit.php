<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$message = "";
$messageType = "";

$sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ../index.php");
    exit();
}

$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = trim($_POST['item_name']);
    $availability = trim($_POST['availability']);
    $category = trim($_POST['category']);
    $rent_per_day = trim($_POST['rent_per_day']);
    $description = trim($_POST['description']);
    $image_name = $post['image'];

    if (empty($item_name) || empty($availability) || empty($category) || empty($rent_per_day) || empty($description)) {
        $message = "All fields except image are required.";
        $messageType = "error";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            $image_name = time() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }

        $update_sql = "UPDATE posts 
                       SET item_name = ?, availability = ?, category = ?, rent_per_day = ?, description = ?, image = ?
                       WHERE id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssdssii", $item_name, $availability, $category, $rent_per_day, $description, $image_name, $post_id, $user_id);

        if ($update_stmt->execute()) {
            header("Location: ../index.php");
            exit();
        } else {
            $message = "Failed to update rental item.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rental Item | OnlineRental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eef2f3, #d9e7ff);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 750px;
        }

        .card {
            background: white;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .top-bar h1 {
            font-size: 28px;
            color: #203a43;
        }

        .back-link {
            text-decoration: none;
            color: #00a6c7;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .subtitle {
            color: #5a6b79;
            margin-bottom: 22px;
            line-height: 1.6;
        }

        .message {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
        }

        .error {
            background: #ffe1e1;
            color: #b42318;
            border: 1px solid #ffb3b3;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #203a43;
        }

        .input-group input,
        .input-group textarea,
        .input-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #d0d7de;
            border-radius: 12px;
            font-size: 15px;
            outline: none;
            transition: 0.3s ease;
        }

        .input-group input:focus,
        .input-group textarea:focus,
        .input-group select:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.18);
        }

        .input-group textarea {
            min-height: 160px;
            resize: vertical;
        }

        .preview-img {
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 10px;
            border: 1px solid #d0d7de;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ffb703, #fb8500);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 18px rgba(251, 133, 0, 0.24);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="top-bar">
                <h1>✏️ Edit Rental Item</h1>
                <a href="../index.php" class="back-link">← Back to Home</a>
            </div>

            <p class="subtitle">Update your rental item details below.</p>

            <?php if (!empty($message)) { ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" value="<?php echo htmlspecialchars($post['item_name']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Availability</label>
                    <select name="availability" required>
                        <option value="Available" <?php if ($post['availability'] == 'Available') echo 'selected'; ?>>Available</option>
                        <option value="Not Available" <?php if ($post['availability'] == 'Not Available') echo 'selected'; ?>>Not Available</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($post['category']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Rent Per Day</label>
                    <input type="number" step="0.01" name="rent_per_day" value="<?php echo htmlspecialchars($post['rent_per_day']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description" required><?php echo htmlspecialchars($post['description']); ?></textarea>
                </div>

                <div class="input-group">
                    <label>Change Image</label>
                    <input type="file" name="image" accept="image/*">
                    <?php if (!empty($post['image'])) { ?>
                        <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" class="preview-img" alt="Item Image">
                    <?php } ?>
                </div>

                <button type="submit" class="btn">Update Rental Item</button>
            </form>
        </div>
    </div>
</body>
</html>