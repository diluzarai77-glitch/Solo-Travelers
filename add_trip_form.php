<!DOCTYPE html>
<html>
<head>
    <title>Plan Trip - Solo Travel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg,#6a11cb,#2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: #fff;
            width: 400px;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: fadeInUp 0.6s ease forwards;
        }
        h2 { text-align: center; margin-bottom: 25px; color: #333; }
        .error-msg { color: red; text-align:center; margin-bottom:12px; }
        label { display: block; margin-top: 15px; font-weight: 600; color: #555; }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }
        input:focus, select:focus { border-color: #6a11cb; box-shadow: 0 0 8px rgba(106,17,203,0.3); }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background: #6a11cb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #5a0fb8; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        .back-link { display:block; text-align:center; margin-top:18px; color:#6a11cb; text-decoration:none; font-weight:500; }
        .back-link:hover { text-decoration: underline; }
        @keyframes fadeInUp { 0%{opacity:0; transform:translateY(20px);} 100%{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Plan Your Trip</h2>

    <?php if(isset($error)){ echo "<p class='error-msg'>$error</p>"; } ?>

    <form method="POST" action="add_trip.php">
        <label>Destination</label>
        <select name="destination" required>
            <option value="">Select destination</option>
            <?php
            $places = ["Poon Hill","Langtang","ABC Trek","Rara Lake","Annapurna Base Camp","Everest Base Camp","Pokhara","Langtang Valley"];
            foreach($places as $p){
                $selected = ($prefill_destination == $p) ? "selected" : "";
                echo "<option value='$p' $selected>$p</option>";
            }
            ?>
        </select>

        <label>Start Date</label>
        <input type="date" name="start_date" required>

        <label>End Date</label>
        <input type="date" name="end_date" required>

        <button type="submit">Save Trip</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
