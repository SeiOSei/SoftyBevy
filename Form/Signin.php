<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="account-style.css">
        <link rel="stylesheet" type="text/css" href="dashboard-style.css">
        <link rel="stylesheet" type="text/css" href="browse-admin-style.css">
        <title>SoftyBevy Login-Register</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="headercontainer">
            <header class="Dashheader">
                <span class="headerspan"><a class="a1" href="Dashboard.php">SoftyBevy</a></span>
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link" aria-current="page">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="Signin.php" class="nav-link active">Log-in/Sign-up</a>
                    </li>
                </ul>
            </header>
        </div>
        <div class="container" style="padding:80px">
            <div class="form-wrapper">
                <div class="form-container login visible">
                    <h2>Login</h2>
                    <form action="login.php" method="post">
                        <div class="input-container">
                            <input type="email" name="email" id="email" required oninput="togglePlaceholder('email')" autocomplete="off" placeholder="Email">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-container">
                            <input type="password" name="password" id="password" required oninput="togglePlaceholder('password')" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <input type="submit" value="Login">
                    </form>
                    <button class="custom-button1" onclick="toggleForms()">Don't have an account? Register here!</button>
                </div>
                <div class="form-container registration hidden">
                    <h2>Register</h2>
                    <form action="register.php" method="post">
                        <div class="input-container">
                            <input type="text" name="firstname" id="firstname" oninput="togglePlaceholder('firstname')" autocomplete="off" required placeholder="First Name">
                            <label for="firstname">First Name</label>
                        </div>
                        <div class="input-container">
                            <input type="text" name="lastname" id="lastname" oninput="togglePlaceholder('lastname')" autocomplete="off"  required placeholder="Last Name">
                            <label for="lastname">Last Name</label>
                        </div>
                        <div class="input-container">
                            <input type="email" name="email" id="email-register" required oninput="togglePlaceholder('email')" autocomplete="off" placeholder="Email">
                            <label for="email-register">Email</label>
                        </div>
                        <div class="input-container">
                            <input type="password" name="password" id="password-register" oninput="togglePlaceholder('password')" required placeholder="Password">
                            <label for="password-register">Password</label>
                        </div>
                        <div id="error-message" class="response-message"></div>
                        <input type="submit" value="Register">
                    </form>
                    <button class="custom-button2" onclick="toggleForms()">Have an account? Login here!</button>
                </div>
            </div>
        </div>
        <script src="account-script.js"></script>
        <script src="dashboard-script.js"></script>
    </body>
</html>