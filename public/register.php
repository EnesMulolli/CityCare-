<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CityCare – Sign Up</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: #f2f5f7;
        }

        .signup-container {
            width: 100%;
            max-width: 480px;
            margin: 60px auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .signup-container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 15px;
            color: #284b63;
        }

        .signup-container p {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
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

        .login-link {
            margin-top: 15px;
            text-align: center;
        }

        .login-link a {
            color: #284b63;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="signup-container">
        <h2>Create an Account</h2>
        <p>Join CityCare and help improve your community</p>

        <form action="../api/signup.php" method="POST">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Surname</label>
                <input type="text" name="surname" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Home Address</label>
                <input type="text" name="location" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Sign Up</button>

            <div class="login-link">
                Already have an account? <a href="login.php">Log In</a>
            </div>

        </form>
    </div>

</body>
</html>
