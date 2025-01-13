
    <?php include 'components/header.php'; ?>

    <style>
        #signinForm .input-group
        {    
            background-color: rgb(255 255 255 / 15%);
            border-color: rgb(255 255 255 / 15%);
        }
        #signinForm .input-group input
        {    
            border: none !important;
            outline: none !important;
            border-radius: 50px;
            
        }
        #signinForm .input-group .password-toggle
        {
            border: none;
            text-decoration: none;
        }
        #inputChoosePassword
        {
            height: 45px;
            border-radius: 50px 0 0 50px !important;
        }
    </style>
    <div class="page-wrapper">
        <div class="page-content">
            <section class="py-3 border-bottom d-none d-md-flex">
                <div class="container">
                    <div class="page-breadcrumb d-flex align-items-center">
                        <h3 class="breadcrumb-title pe-3">Tizimga Kirish</h3>
                        <div class="ms-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <div class="d-grid">
                                        <a href="../partners/login.php" class="btn btn-light">
                                            <i class="bx bxs-user"></i> Partner sifatida kirish
                                        </a>
                                    </div>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section class="">
                <div class="container">
                    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                        <div class="row row-cols-1 row-cols-xl-2">
                            <div class="col mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="border p-4 rounded">
                                            <div class="text-center">
                                                <h3 class="">Kirish</h3>
                                                <p>Hali Ro'yxatdan O'tmaganmisiz? <a href="signup.php" class="to-sign-in">Unda bu yerni bosing</a></p>
                                            </div>
                                            <div class="login-separater text-center mb-4">
                                                <span>YOKI E-Pochta BILAN KIRISH</span>
                                                <hr />
                                            </div>
                                            <div class="form-body">
                                                <form class="row g-3" id="signinForm">
                                                    <div class="col-12">
                                                        <label for="inputEmailAddress" class="form-label">E-pochta manzili</label>
                                                        <div class="input-group col-12">
                                                            <input name="email" type="email" class="form-control" id="inputEmailAddress" placeholder="Email" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="inputChoosePassword" class="form-label">Parolni kiriting</label>
                                                        <div class="input-group" id="show_hide_password">
                                                            <input name="password" type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Parol" required>
                                                            <a href="javascript:;" class="input-group-text bg-transparent password-toggle"><i class='bx bx-hide'></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div id="error_message" class="alert alert-danger d-none"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-switch"></div>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <a href="forgot-password.php">Parolni Unutdingizmi ?</a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-light"><i class="bx bxs-lock-open"></i>Tizimga Kirish</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>



    <script src="js/signin.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
    <script src="assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>