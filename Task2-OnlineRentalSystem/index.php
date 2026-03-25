<?php
session_start();
include 'config/db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineRental | Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #eef2f3, #d9e7ff);
            min-height: 100vh;
            color: #1f2d3a;
        }

        .navbar {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            flex-wrap: wrap;
            gap: 10px;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #7ee8fa;
        }

        .hero {
            max-width: 1100px;
            margin: 50px auto 20px;
            padding: 0 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 12px;
            color: #203a43;
        }

        .hero p {
            font-size: 17px;
            color: #4b5d6b;
            max-width: 760px;
            margin: 0 auto 28px;
            line-height: 1.7;
        }

        .welcome-box {
            background: white;
            max-width: 760px;
            margin: 0 auto 30px;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0,0,0,0.08);
        }

        .welcome-box h2 {
            color: #1d3557;
            margin-bottom: 10px;
        }

        .welcome-box p {
            font-size: 16px;
        }

        .buttons {
            margin-top: 22px;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            margin: 8px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00c9a7, #00b4d8);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #203a43;
            border: 2px solid #203a43;
        }

        .btn-edit {
            background: #ffb703;
            color: #222;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 8px;
        }

        .btn-delete {
            background: #e63946;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }

        .posts-section {
            max-width: 1100px;
            margin: 30px auto 60px;
            padding: 0 20px;
        }

        .posts-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #203a43;
        }

        .post-card {
            background: white;
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .post-card h3 {
            margin-bottom: 10px;
            color: #203a43;
        }

        .post-meta {
            font-size: 14px;
            color: #6b7a88;
            margin-bottom: 12px;
        }

        .post-card p {
            font-size: 15px;
            line-height: 1.7;
            color: #44525f;
            margin-bottom: 18px;
        }

        .empty-posts {
            background: white;
            padding: 24px;
            border-radius: 18px;
            text-align: center;
            color: #5a6b79;
            box-shadow: 0 8px 22px rgba(0,0,0,0.08);
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 18px 20px;
            }

            .hero h1 {
                font-size: 32px;
            }

            .nav-links a {
                margin-left: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">🚗 OnlineRental</div>
        <div class="nav-links">
            <a href="index.php">Home</a>

            <?php if ($isLoggedIn): ?>
                <a href="posts/create.php">Create Post</a>
                <a href="auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="auth/register.php">Register</a>
                <a href="auth/login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero">
        <h1>Welcome to OnlineRental</h1>
        <p>
            A PHP and MySQL based CRUD application where users can register, login,
            and publish content related to rental items and services.
        </p>

        <div class="welcome-box">
            <?php if ($isLoggedIn): ?>
                <h2>Hello, <?php echo htmlspecialchars($username); ?> 👋</h2>
                <p>You are logged in successfully. Now you can create, edit, and delete your posts.</p>
                <div class="buttons">
                    <a href="posts/create.php" class="btn btn-primary">Create a Post</a>
                    <a href="auth/logout.php" class="btn btn-secondary">Logout</a>
                </div>
            <?php else: ?>
                <h2>Hello, Guest 👋</h2>
                <p>Please register or login to access the CRUD features of this project.</p>
                <div class="buttons">
                    <a href="auth/register.php" class="btn btn-primary">Register</a>
                    <a href="auth/login.php" class="btn btn-secondary">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="posts-section">
        <h2 class="posts-title">Latest Posts</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post-card">
    <?php if (!empty($row['image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image" style="width:100%; max-width:300px; height:200px; object-fit:cover; border-radius:12px; margin-bottom:15px;">
    <?php endif; ?>

    <h3>
        <a class="post-title-link" href="posts/view.php?id=<?php echo $row['id']; ?>">
            <?php echo htmlspecialchars($row['item_name']); ?>
        </a>
    </h3>

    <div class="post-meta">
        Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo $row['created_at']; ?>
    </div>

    <p><strong>Availability:</strong> <?php echo htmlspecialchars($row['availability']); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
    <p><strong>Rent Per Day:</strong> ₹<?php echo htmlspecialchars($row['rent_per_day']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

    <?php if ($isLoggedIn && $_SESSION['user_id'] == $row['user_id']): ?>
        <a href="posts/edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
        <a href="posts/delete.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
    <?php endif; ?>
</div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-posts">
                No posts available yet. Be the first one to create a post!
            </div>
        <?php endif; ?>
    </div>

</body>
</html>