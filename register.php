<?php 

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    */
    
    require_once 'global.php';

    $fl_duplicateEmail = (isset($_GET['error']) && isset($_GET['error']) == 'duplicatedEmail');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

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

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account on WIMM!</h1>
                            </div>

                            <?php if ($fl_duplicateEmail) {?>
                            <div class="alert alert-warning" role="alert">
                                Email already registered. Try a new one.
                            </div>
                            <?php } ?>

                            <!-- User Form -->
                            <form id="registerForm" method="post" action="php/register-user.php" class="user needs-validation" novalidate>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="InputFullName"
                                        placeholder="Full Name" required autofocus>
                                    
                                    <div class="invalid-feedback">
                                        Please provide a name.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="InputEmail"
                                        placeholder="Email Address" required>

                                    <div class="invalid-feedback">
                                        Please provide an email.
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user pwds"
                                        id="InputPassword" name="InputPassword" placeholder="Password" required>
                                    </div>

                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user pwds"
                                            id="InputRepeatPassword" placeholder="Repeat Password" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div id="cPwdValid" class="valid-feedback">Password confirmed</div>

                                    <div id="cPwdInvalid" class="invalid-feedback">
                                        Please choose a password.
                                    </div>
                                </div>

                                <button id="submitBtn" class="btn btn-primary btn-user btn-block" type="submit" disabled>Register Account</button>

                                <hr>
                                
                                <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id=600848429729-qgr22hc49ejets7emkmpv5e3rlj7vg9u.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%2Fbelvo%2Fphp%2Fredirect.php&state&scope=email%20profile&approval_prompt=auto" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                            </form>
                            <!-- /. User Form -->
                            
                            <hr>

                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
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

        $('#InputPassword, #InputRepeatPassword').on('keyup', function () {
                if ($('#InputPassword').val() != '' && $('#InputRepeatPassword').val() != '' && $('#InputPassword').val() == $('#InputRepeatPassword').val()) {
                    $("#submitBtn").attr("disabled",false);
                    $('#cPwdValid').show();
                    $('#cPwdInvalid').hide();
                    $('#cPwdValid').html('Valid').css('color', 'green');
                    $('.pwds').removeClass('is-invalid')
                } else {
                    $("#submitBtn").attr("disabled",true);
                    $('#cPwdValid').hide();
                    $('#cPwdInvalid').show();
                    $('#cPwdInvalid').html('Not Matching').css('color', 'red');
                    $('.pwds').addClass('is-invalid')
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