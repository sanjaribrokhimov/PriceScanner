<!DOCTYPE html>
<html lang="en" class="white">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Partners</title>
    <!-- Styles -->
    <link href="rcAdmin/assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="rcAdmin/assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="rcAdmin/assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="rcAdmin/assets/css/lib/unix.css" rel="stylesheet">
    <link href="rcAdmin/assets/css/style.css" rel="stylesheet">
   <script>
    var local_url = ''
   </script>
</head>

<body style="background-color:dodgerblue;" class="bg-primary">

<div class="unix-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="index.php"><span>Hello Admin</span></a>
                        </div>
                        <div class="login-form">
                            <h4>Administratior Login</h4>
                            <form id="loginForm">
                                <div class="form-group">
                                    <label>Login</label>
                                    <input type="email" id="email" class="form-control" placeholder="Login">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="checkbox">
                                </div>
                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        

    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const loginData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        fetch(`${local_url}/api/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(loginData)
        })
        .then((response) => {
            if (response.status == 200) {
                return response.json();
            } else {
                alert('Login failed: ' + response.message);
                throw new Error('Login failed');
            }
        })
        .then((data) => {
            localStorage.setItem('access_token', data.access_token);
            localStorage.setItem('email', data.email);
            localStorage.setItem('first_name', data.first_name);
            localStorage.setItem('last_name', data.last_name);
            localStorage.setItem('phone_number', data.phone_number);
            localStorage.setItem('role', data.role);
            localStorage.setItem('city', data.city);
            localStorage.setItem('category', data.category);
            localStorage.setItem('company_id', data.company_id);

            // Проверка категории
            if (data.category === 'rentcar') {
                window.location.href = './rcAdmin/index.php'; 
            } else if (data.category === 'tour') {
                window.location.href = './tourAdmin/index.php'; 
            } else if (data.category === 'hotel') {
                window.location.href = './hotelAdmin/index.php'; 
            } else {
                alert("Unknown category: " + data.category);
            }
        })
        .catch(error => {
            alert("Error occured, please try again later.");
        });

    });
</script>


</body>

</html>
