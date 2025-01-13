<?php include 'components/header.php' ?>
  <div class="page-wrapper">
    <div class="page-content">
      <section class="py-4">
        <div class="container">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="card shadow-none mb-3 mb-lg-0">
                    <div class="card-body">
                      <div class="list-group list-group-flush">
                        <a href="dashboard_user.php" class="list-group-item active d-flex justify-content-between align-items-center">
                          Boshqaruv Paneli <i class='bx bx-tachometer fs-5'></i>
                        </a>
                        <a href="user_settings.php" class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
                          Account Sozlamasi <i class='bx bx-user-circle fs-5'></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="card shadow-none mb-0">
                    <div class="card-body">
                      <p>Salom <strong id="user-full-name"></strong>!</p>
                      <p>Boshqaruv paneliga xush kelibsiz. Sizning tafsilotlaringiz:</p>
                      <ul>
                        <li><strong>FIO:</strong> <span id="full_name"></span></li>
                        <li><strong>Email:</strong> <span id="user-email"></span></li>
                        <li><strong>Telefon raqami:</strong> <span id="user-phone"></span></li>
                        <li><strong>Shahar:</strong> <span id="user-city"></span></li>
                      </ul>
                      <p id="error-message" style="color: red;"></p>
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
  <script>
    document.getElementById('user-full-name').textContent = localStorage.getItem("first_name") + " " + localStorage.getItem("last_name");
    document.getElementById('full_name').textContent = localStorage.getItem("first_name") + " " + localStorage.getItem("last_name");
    document.getElementById('user-email').textContent = localStorage.getItem("email");
    document.getElementById('user-phone').textContent = localStorage.getItem("phone_number");
    document.getElementById('user-city').textContent = localStorage.getItem("city");
  </script>
  <script src="js/app.js"></script>
  <script>
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
