<?php include 'components/header.php'; ?>
    <div class="page-wrapper">
        <div class="page-content">
            <section class="py-3 border-bottom d-none d-md-flex">
                <div class="container">
                    <div class="page-breadcrumb d-flex align-items-center">
                        <h3 class="breadcrumb-title pe-3">Parolingizni unutdingizmi</h3>
                        <div class="ms-auto">
                            <nav aria-label="breadcrumb">
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="">
                <div class="container">
                    <div class="authentication-forgot d-flex align-items-center justify-content-center">
                        <div class="card forgot-box">
                            <div class="card-body">
                                <div class="p-4 rounded border">
                                    <div class="text-center">
                                        <img src="assets/images/icons/forgot-2.png" width="120" alt="" />
                                    </div>
                                    <h4 class="mt-5 font-weight-bold">Parolingizni unutdingizmi?</h4>
                                    <p>Parolni tiklash uchun ro'yxatdan o'tgan elektron pochta manzilingizni kiriting</p>
                                    
                                    <form id="resetPasswordForm">
                                        <div class="my-4">
                                            <label class="form-label">Email</label>
                                            <input id="email" name="email" type="email" class="form-control form-control-lg" placeholder="email@user.com" required />
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-light btn-lg">Yuborish</button> 
                                            <a href="signin.php" class="btn btn-light btn-lg">
                                                <i class='bx bx-arrow-back me-1'></i> Qaytish
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>

        var local_url = '';
        fetch('../local_api.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Tarmoq xatosi');
                }
                return response.json();
            })
            .then(data => {
                local_url = data.url
            })
            .catch(error => {
                console.error('JSONni o\'qishda xato:', error);
            });



        document.getElementById('resetPasswordForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const email = document.getElementById('email').value;
            const errorElement = document.getElementById('error');
            
            if (!validateEmail(email)) {
                errorElement.textContent = 'Email formati noto\'g\'ri';
                return;
            }

            localStorage.setItem('user_email', email);

            try {
                const response = await fetch(`${local_url}/api/auth/reset_password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });

                if (!response.ok) {
                    throw new Error('Tarmoq xatosi');
                }

                window.location.href = 'otp-forget.php';
            } catch (error) {
                console.error('Xato:', error);
                errorElement.textContent = 'So\'rov yuborishda xato';
            }
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
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