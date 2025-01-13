<?php include 'components/header.php'; ?>
    <div class="page-wrapper">
        <div class="page-content">
            <section class="py-0 py-lg-5">
                <div class="container">
                    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                        <div class="row row-cols-1 row-cols-lg-1 row-cols-xl-2">
                            <div class="col mx-auto">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="border p-4 rounded">
                                            <div class="text-center">
                                                <h3 class="">E-Pochta Tasdiqlash</h3>
                                                <p>Pochtangizga kelgan 6 xonalik kodni kiriting</p>
                                            </div>
                                            <div class="form-body">
                                                <div class="container">
                                                    <form id="verificationForm" class="row g-3">
                                                        <div class="col-12">
                                                            <label for="inputVerificationCode" class="form-label">Tasdiqlash KODI</label>
                                                            <input type="text" class="form-control" id="inputVerificationCode" name="verification_code" placeholder="- - - - - -" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="inputNewPassword" class="form-label">Yangi Parol</label>
                                                            <input type="password" class="form-control" id="inputNewPassword" name="new_password" placeholder="Yangi parol" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="inputConfirmPassword" class="form-label">Parolni Tasdiqlash</label>
                                                            <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" placeholder="Parolni tasdiqlash" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <button type="submit" class="btn btn-light"><i class='bx bx-user'></i>Tasdiqlash</button>
                                                            </div>
                                                            <br>
                                                            <div class="d-grid gap-2">
                                                                <a href="forgot-password.php" class="btn btn-light btn-lg">
                                                                    <i class='bx bx-arrow-back me-1'></i> Qaytish
                                                                </a>
                                                            </div>
                                                            <br>
                                                            <div class="d-grid gap-2">
                                                                <a href="signin.php" class="btn btn-light btn-lg">
                                                                     Bosh saxifa
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div id="alertMessages"></div>
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

    </div>
   
    <script>
        document.getElementById('verificationForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const verificationCode = document.getElementById('inputVerificationCode').value;
            const newPassword = document.getElementById('inputNewPassword').value;
            const confirmPassword = document.getElementById('inputConfirmPassword').value;
            const alertMessages = document.getElementById('alertMessages');

            const errors = [];
            if (verificationCode.length < 6) {
                errors.push('Tasdiqlash kodi kamida 6 raqamdan iborat bo\'lishi kerak.');
            }
            if (newPassword.length < 5 || newPassword.length > 10) {
                errors.push('Yangi parol 5-10 ta belgidan iborat bo\'lishi kerak.');
            }
            if (newPassword !== confirmPassword) {
                errors.push('Yangi parol va parolni tasdiqlash mos kelmaydi.');
            }

            if (errors.length > 0) {
                alertMessages.innerHTML = '<div class="alert alert-danger">' + errors.join('<br>') + '</div>';
                return;
            }

            const email = localStorage.getItem('user_email');

            if (!email) {
                alertMessages.innerHTML = '<div class="alert alert-danger">Email manzil mavjud emas.</div>';
                return;
            }

            try {
                const response = await fetch(`${local_url}/api/auth/verify_pr`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email,
                        password: newPassword,
                        otp: verificationCode
                    }),
                });

                if (!response.ok) {
                    throw new Error('Ошибка сети');
                }

                const result = await response.json();

                if (result.message === 'OTP verified. Password change complete.') {
                    localStorage.removeItem('user_email');
                    alertMessages.innerHTML = '<div class="alert alert-success">Parolingiz muvaffaqiyatli yangilandi. Kirish uchun bosh saxifaga bosing.</div>';
                    setTimeout(() => {
                        window.location.href = 'signin.php';
                    }, 2000);
                } else {
                    alertMessages.innerHTML = '<div class="alert alert-danger">Xato: ' + (result.error || 'Noma\'lum xato yuz berdi.') + '</div>';
                }
            } catch (error) {
                alertMessages.innerHTML = '<div class="alert alert-danger">Xato yuz berdi. Iltimos, qayta urinib ko\'ring.</div>';
            }
        });
    </script>
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
