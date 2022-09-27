<?php 

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    */
    require_once 'vendor/autoload.php';    
    require_once 'global.php';

    //Setting flags to show Errors
    $fl_userNotFound = (isset($_GET['error']) && $_GET['error'] == 'userNotFound');
    $fl_accessDenied = (isset($_GET['error']) && $_GET['error'] == 'accessDenied');
    $fl_formError = (isset($_GET['error']) && $_GET['error'] == 'formError');
             
    // init configuration - Setting Google IDs to create the Google Login Button
    $clientID = $_ENV['GOOGLE_API_CLIENT_ID'];
    $clientSecret = $_ENV['GOOGLE_API_CLIENT_SECRET'];;
    $redirectUri = $_ENV['GOOGLE_URL_REDIRECT'];
        
    // create Client Request to access Google API
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Diogo Carvalho">

    <title><?php echo $_ENV['APP_NAME']; ?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back to WIMM!</h1>
                                    </div>

                                    <?php if ($fl_userNotFound) { ?>
                                        <div class="alert alert-warning" role="alert">
                                        Email not found or password is invalid
                                        </div>
                                    <?php } ?>

                                    <?php if ($fl_accessDenied) { ?>
                                        <div class="alert alert-warning" role="alert">
                                            Email not registered. Please try to register before login
                                        </div>
                                    <?php } ?>

                                    <form class="user" method="post" action="php/login-user.php" needs-validation novalidate>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="InputEmail" name="InputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." required>
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"not
                                                id="InputPassword" name="InputPassword" placeholder="Password" required>
                                        </div>

                                        <button id="submitBtn" class="btn btn-primary btn-user btn-block" type="submit" disabled>Login</button>
                                        
                                        <hr>
                                        
                                        <a href="<?php echo $client->createAuthUrl(); ?>" 
                                            class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                    </form>

                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script type="text/javascript">

        $('#InputPassword, #InputEmail').on('keyup', function () {
            if ($('#InputEmail').val() != '' && $('#InputPassword').val() != '') {
                $("#submitBtn").attr("disabled",false);
            } else {
                $("#submitBtn").attr("disabled",true);
            }
        });

        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
                })
            })()
    </script>

</body>

</html>