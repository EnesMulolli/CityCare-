<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CityCare – Login</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: #f2f5f7;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 80px auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .login-container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 15px;
            color: #284b63;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: 600;
            color: #284b63;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #d1d1d1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            border-color: #284b63;
            outline: none;
            box-shadow: 0 0 5px rgba(40, 75, 99, 0.3);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #284b63;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #3c6e71;
        }

        .signup-link {
            margin-top: 15px;
            text-align: center;
        }

        .signup-link a {
            color: #284b63;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>

        <form action="../api/login.php" method="POST">

            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="user" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Log In</button>

            <div class="signup-link">
                Don’t have an account? <a href="register.php">Sign Up</a>
            </div>

        </form>
    </div>

</body>
</html>
