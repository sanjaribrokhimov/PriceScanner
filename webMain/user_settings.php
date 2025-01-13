
    <header id="header-content"></header>
    <div class="page-wrapper">
    <div class="page-content">

    
        <section class="py-4">
            <div class="container">
                <h3 class="d-none">Account</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card shadow-none mb-3 mb-lg-0">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            <!-- Navigation links -->
                                            <a href="dashboard_user.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Boshqaruv Paneli<i class='bx bx-tachometer fs-5'></i></a>
                                            <a href="user_settings.php" class="list-group-item active d-flex justify-content-between align-items-center">Account sozlamasi<i class='bx bx-user-circle fs-5'></i></a>
                                            <a href="account-orders.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Buyurtmalar<i class='bx bx-cart-alt fs-5'></i></a>
                                            <a href="account-downloads.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Yuklanishlar<i class='bx bx-download fs-5'></i></a>
                                            <a href="account-addresses.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Manzillar<i class='bx bx-home-smile fs-5'></i></a>
                                            <a href="account-payment-methods.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Tolovlar<i class='bx bx-credit-card fs-5'></i></a>
                                            <a href="#" class="list-group-item d-flex justify-content-between align-items-center bg-transparent" onclick="confirmLogout()">Chiqish<i class='bx bx-log-out fs-5'></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card shadow-none mb-0">
                                    <div class="card-body">
                                        <!-- User settings form -->
                                        <form class="row g-3" id="updateForm">
                                            <div class="col-md-6">
                                                <label class="form-label">Ism</label>
                                                <input type="text" class="form-control" name="first_name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Familiya</label>
                                                <input type="text" class="form-control" name="last_name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Telefon raqami</label>
                                                <input type="text" class="form-control" name="phone_number" required>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">Yangi parol</label>
                                                <input type="password" class="form-control" name="new_password">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Parolni tasdiqlang</label>
                                                <input type="password" class="form-control" name="confirm_password">
                                            </div>
                                            <div class="col-12">
                                                <div id="message" class="text-white p-2" style="display: none;"></div>
                                            </div>
                                            <div class="col-12">
                                                <button type="button" class="btn btn-light btn-ecomm" id="saveBtn">Saqlash</button>
                                            </div>
                                        </form>
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
<footer id="footer-content"></footer>
<script src="js/app.js"></script>
<script>
    function populateFormFromLocalStorage() {
        document.querySelector('input[name="first_name"]').value = localStorage.getItem("first_name") || '';
        document.querySelector('input[name="last_name"]').value = localStorage.getItem("last_name") || '';
        document.querySelector('input[name="phone_number"]').value = localStorage.getItem("phone_number") || '';
    }

    document.addEventListener('DOMContentLoaded', populateFormFromLocalStorage);

    function confirmLogout() {
        if (confirm('Accountdan chiqmoqchisiz ishonchingiz komilmi ?')) {
            window.location.href = 'logout.php';
        }
    }
    document.getElementById('saveBtn').addEventListener('click', function() {
        const myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        myHeaders.append("Authorization", `Bearer ${localStorage.getItem("access_token")}`);

        const form = document.getElementById('updateForm');
        const formData = new FormData(form);
        const newPassword = formData.get('new_password');
        const confirmPassword = formData.get('confirm_password');
        const messageDiv = document.getElementById('message');

        messageDiv.style.display = 'none';
        messageDiv.classList.remove('bg-danger', 'bg-success');

        for (let [name, value] of formData.entries()) {
            if (!value && name !== 'new_password' && name !== 'confirm_password') {
                messageDiv.textContent = "Iltimos barcha ma'lumotlarni kiriting.";
                messageDiv.classList.add('bg-danger');
                messageDiv.style.display = 'block';
                return;
        }
        }

        if (newPassword || confirmPassword) {
            if (newPassword !== confirmPassword) {
                messageDiv.textContent = 'Yangi parollar bir biriga mos emas .';
                messageDiv.classList.add('bg-danger');
                messageDiv.style.display = 'block';
                return;
            }
        }

        const raw = JSON.stringify({
            "first_name": formData.get("first_name"),
            "last_name": formData.get("last_name"),
            "phone_number": formData.get("phone_number"),
            "password": newPassword || undefined
        });

        const requestOptions = {
            method: "POST",
            headers: myHeaders,
            body: raw,
            redirect: "follow"
        };

    fetch(`${local_url}/api/auth/user/update`, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Server response wasn\'t OK');
            }
            return response.json();
        })
        .then(result => {
            if (newPassword) {
                userData.password = newPassword;
            }
            localStorage.setItem("first_name", formData.get("first_name"))
            localStorage.setItem("last_name", formData.get("last_name"))
            localStorage.setItem("phone_number", formData.get("phone_number"))

            messageDiv.textContent = result.message;
            messageDiv.classList.add('bg-success');
            messageDiv.style.display = 'block';
        })
        .catch(error => {
            messageDiv.textContent = 'Ma\'lumotlarni o\'zgartirishda xato: ' + error;
            messageDiv.classList.add('bg-danger');
            messageDiv.style.display = 'block';
        }); 
    });
    app("components/header.php", "header-content")
    app("components/footer.php", "footer-content")
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
