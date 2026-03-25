<?php
session_start();
include '../config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$post_id = intval($_GET['id']);
$isLoggedIn = isset($_SESSION['user_id']);

$sql = "SELECT posts.*, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ../index.php");
    exit();
}

$post = $result->fetch_assoc();
$isOwner = $isLoggedIn && $_SESSION['user_id'] == $post['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post | OnlineRental</title>
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
            padding: 30px 20px;
            color: #1f2d3a;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            font-size: 30px;
            color: #203a43;
            font-weight: bold;
        }

        .back-link {
            text-decoration: none;
            color: #00a6c7;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .post-card {
            background: white;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .post-card h1 {
            font-size: 34px;
            color: #203a43;
            margin-bottom: 14px;
            line-height: 1.3;
        }

        .post-meta {
            font-size: 14px;
            color: #6b7a88;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e4ebf0;
        }

        .post-content {
            font-size: 17px;
            line-height: 1.9;
            color: #44525f;
            white-space: pre-line;
            margin-bottom: 28px;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-edit {
            background: #ffb703;
            color: #222;
        }

        .btn-delete {
            background: #e63946;
            color: white;
        }

        .btn-home {
            background: linear-gradient(135deg, #00c9a7, #00b4d8);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }

        @media (max-width: 600px) {
            .post-card {
                padding: 24px;
            }

            .post-card h1 {
                font-size: 28px;
            }

            .page-title {
                font-size: 24px;
            }

            .post-content {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <div class="page-title">📖 Post Details</div>
            <a href="../index.php" class="back-link">← Back to Home</a>
        </div>

        <div class="post-card">
            <h1><?php echo htmlspecialchars($post['item_name']); ?></h1>

<div class="post-meta">
    Posted by <strong><?php echo htmlspecialchars($post['username']); ?></strong>
    on <?php echo htmlspecialchars($post['created_at']); ?>
</div>

<?php if (!empty($post['image'])): ?>
    <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Item Image" style="width:100%; max-width:400px; height:250px; object-fit:cover; border-radius:12px; margin-bottom:20px;">
<?php endif; ?>

<div class="post-content">
    <p><strong>Availability:</strong> <?php echo htmlspecialchars($post['availability']); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
    <p><strong>Rent Per Day:</strong> ₹<?php echo htmlspecialchars($post['rent_per_day']); ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
</div>

            <div class="actions">
                <a href="../index.php" class="btn btn-home">Home</a>

                <?php if ($isOwner): ?>
                    <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-delete"
                       onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>