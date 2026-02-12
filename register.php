<?php
include 'db.php';
session_start();

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    // Optional: basic validation
    if($name=="" || $email=="" || $password==""){
        $error = "All fields are required!";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')";
        if($conn->query($sql)){
            echo "<script>alert('Registered Successfully!'); window.location='index.php';</script>";
            exit;
        } else {
            $error = $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Solo Travel</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
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

        /* LOGIN LINK */
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

        /* ANIMATION */
        @keyframes fadeInUp {
            0% {opacity:0; transform: translateY(20px);}
            100% {opacity:1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="center-container fadeIn">
    <div class="card">
        <h2>Register</h2>

        <?php if(isset($error)){ echo "<p class='error-msg'>$error</p>"; } ?>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <a href="index.php">Already have an account? Login</a>
    </div>
</div>

</body>
</html>
