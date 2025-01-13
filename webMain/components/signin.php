<div class="page-wrapper">
    <div class="page-content">
        <section class="py-3 border-bottom d-none d-md-flex">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Tizimga Kirish</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
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
                                            <p>Hali Ro'yxatdan O'tmaganmisiz? <a href="signup.html">Unda bu yerni bosing</a></p>
                                        </div>
                                        <div class="login-separater text-center mb-4">
                                            <span>YOKI E-Pochta BILAN KIRISH</span>
                                            <hr/>
                                        </div>
                                        <div class="form-body">
                                            <form class="row g-3" id="signinForm">
                                                <div class="col-12">
                                                    <label for="inputEmailAddress" class="form-label">E-pochta manzili</label>
                                                    <input name="email" type="email" class="form-control" id="inputEmailAddress" placeholder="Email" required>
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
                                                    <a href="forgot-password.html">Parolni Unutdingizmi ?</a>
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
