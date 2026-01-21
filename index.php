<!DOCTYPE html>
<html>
<head>
    <title>Lab Management System</title>
    <style>
        /* GLOBAL STYLES */
        :root {
            --primary: #009688;
            --primary-dark: #00796b;
            --bg-body: #f4f6f9;
            --text-color: #333;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: var(--text-color);
        }
        .container {
            background-color: white;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 380px;
            border: 1px solid #eaeaea;
        }
        h1 {
            margin-top: 0;
            color: var(--primary);
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.5;
        }
        .btn-link {
            display: block;
            width: 100%;
            padding: 14px 0;
            margin-bottom: 15px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.3s;
            box-sizing: border-box;
            border: none;
        }
        .btn-link:hover {
            background-color: var(--primary-dark);
        }
        .btn-secondary {
            background-color: #546e7a;
        }
        .btn-secondary:hover {
            background-color: #455a64;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #aaa;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eee;
            margin: 25px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Lab Management</h1>
        <p>Welcome to the Laboratory Issue Tracking.</p>
        
        <hr>

        <a href="complaint.php" class="btn-link">Report an Issue</a>
        <a href="admin_login.php" class="btn-link btn-secondary">Admin Login</a>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> Lab Admin System
        </div>
    </div>

</body>
</html>