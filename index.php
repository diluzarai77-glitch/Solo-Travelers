<?php
include 'db.php';
session_start();

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user;

    if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}
 
    else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Solo Travel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* BODY */
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg,#6a11cb,#2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* CARD */
        .center-container {
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.6s ease forwards;
        }

        .card {
            background: #fff;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* ERROR MESSAGE */
        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 12px;
            font-weight: 500;
        }

        /* FORM INPUTS */
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106,17,203,0.3);
        }

        /* BUTTON */
        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #6a11cb;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a0fb8;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        /* REGISTER LINK */
        a {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #6a11cb;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            0% {opacity:0; transform: translateY(20px);}
            100% {opacity:1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="center-container fadeIn">
    <div class="card">
        <h2>Login</h2>

        <?php if(isset($error)){ echo "<p class='error-msg'>$error</p>"; } ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <a href="register.php">Create an Account</a>
    </div>
</div>

</body>
</html>
