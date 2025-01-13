
    <?php include 'components/header.php'; ?>


    <style>
        #registrationForm input::placeholder
        {
            color: #aaa !important;
        }
        #show_hide_password1,
        #show_hide_password2
        {
            background: transparent;
            border: 1px solid rgba(255 255 255 / .5) !important;
            display: flex;
            align-items: center;
        }
        #show_hide_password1 input,
        #show_hide_password2 input
        {
            border-radius: 50px 0 0 50px;
            height: 47px;
            border: none !important;
            outline: none !important;
        }
        #show_hide_password1 a,
        #show_hide_password2 a
        {
            border: none !important;
            outline: none !important;
        }
    </style>
<div class="page-wrapper">
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom d-none d-md-flex">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Ro'yxatdan o'tish</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">

                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--start shop cart-->
        <br>
        <br>
        <br>
        <section class="py-0 py-lg-5">
            <div class="container">
                <div
                    class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                    <div class="row row-cols-1 row-cols-lg-1 row-cols-xl-2">
                        <div class="col mx-auto">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="border p-4 rounded">
                                        <div class="text-center">
                                            <h3 class="">Ro'yxatdan o'tish</h3>
                                            <p>Akkauntingiz Bormi ? <a href="signin.php" class="to-sign-in">Bu yerdan Tizimga Kiring</a>
                                            </p>
                                        </div>

                                        <div class="login-separater text-center mb-4"> <span>YOKI E-Pochta orqali
                                                ro'yxatdan o'ting</span>
                                            <hr />
                                        </div>
                                        <div class="form-body">

                                            <form class="row g-3" id="registrationForm" action="otp.php" method="GET">
                                                <div class="col-sm-6">
                                                    <label for="inputFirstName" class="form-label">Ism</label>
                                                    <input type="text" class="form-control" id="inputFirstName"
                                                        name="first_name" placeholder="Sanjar">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="inputLastName" class="form-label">Familiya</label>
                                                    <input type="text" class="form-control" id="inputLastName"
                                                        name="last_name" placeholder="Ibrohimov">
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputEmailAddress" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="inputEmailAddress"
                                                        name="email" placeholder="Email@user.com">
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputPhoneNumber" class="form-label">Telefon
                                                        Raqam</label>
                                                    <input type="text" class="form-control" id="inputPhoneNumber"
                                                        name="phone_number" placeholder="+998331108810">
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputSelectCountry" class="form-label">Shahar</label>
                                                    <select class="form-select" id="inputSelectCountry" name="city"
                                                        aria-label="Default select example">
                                                        <option selected>Toshkent</option>
                                                        <option value="1">Namangan</option>
                                                        <option value="2">Andijon</option>
                                                        <option value="3">Farg'ona</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputChoosePassword" class="form-label">Parol</label>
                                                    <div class="input-group" id="show_hide_password1">
                                                        <input type="password" class="form-control border-end-0"
                                                            id="inputPassword1" name="password" placeholder="Parol">
                                                        <a href="javascript:;"
                                                            class="input-group-text bg-transparent password-toggle"
                                                            onclick="togglePasswordVisibility('inputPassword1', 'togglePassword1')">
                                                            <i id="togglePassword1" class='bx bx-hide'></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputConfirmPassword" class="form-label">Parolni
                                                        Tasdiqlash</label>
                                                    <div class="input-group" id="show_hide_password2">
                                                        <input type="password" class="form-control border-end-0"
                                                            id="inputPassword2" name="password2"
                                                            placeholder="Parolni Tasdiqlash">
                                                        <a href="javascript:;"
                                                            class="input-group-text bg-transparent password-toggle"
                                                            onclick="togglePasswordVisibility('inputPassword2', 'togglePassword2')">
                                                            <i id="togglePassword2" class='bx bx-hide'></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexSwitchCheckChecked" name="terms_agreed">
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Men
                                                            Talab va Shartlarni o‘qib chiqdim va
                                                            roziman</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="alert" id="messageBox" style="display: none;"></div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-light" id="submitBtn"
                                                            onclick="validateForm(event)"><i
                                                                class='bx bx-user'></i>Ro'yxatdan O'tish</button>
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
<br>
<br>
<br>
<br>
  </script>
  <script src="js/signup.js"></script>
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

