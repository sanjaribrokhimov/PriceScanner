<?php include 'components/header.php'; ?>
    <div class="page-wrapper">
        <div class="page-content">
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
                                                <h3 class="">E-Pochta Tasdiqlash</h3>
                                                <p>Pochtangizga kelgan 6 xonalik kodni kiriting</p>
                                            </div>
                                            <script>
                                                function validateVerificationCode(event) {
                                                    const verificationCode = document.getElementById('inputVerificationCode').value;
                                                    const codePattern = /^\d{6,}$/;
                                                    const message = document.getElementById('message');

                                                    if (!verificationCode) {
                                                        message.textContent = "Tasdiqlash kodi bo'sh bo'lmasligi kerak";
                                                        message.style.color = 'white';
                                                        event.preventDefault();
                                                        return false;
                                                    }

                                                     else {
                                                        message.textContent = "Tasdiqlash kodi faqat raqamlardan iborat bo'lishi va kamida 6 ta belgidan iborat bo'lishi kerak";
                                                        message.style.color = 'white';
                                                        event.preventDefault();
                                                        return false;
                                                    }
                                                }
                                            </script>
                                            <div class="form-body">
                                                <div class="container">
                                                    <form class="row g-3" id="registrationForm"
                                                        action="dashboard_user.php" method="POST"
                                                        onsubmit="return validateVerificationCode(event)">
                                                        <div class="col-sm-6"></div>
                                                        <div class="col-sm-6"></div>
                                                        <div class="col-12"></div>
                                                        <div class="col-12"></div>
                                                        <div class="col-12"></div>
                                                        <div class="col-12"></div>

                                                        <div class="col-12">
                                                            <label for="inputVerificationCode"
                                                                class="form-label">Tasdiqlash KODI</label>
                                                            <input type="text" class="form-control"
                                                                id="inputVerificationCode" name="verification_code"
                                                                placeholder="- - - - - -">
                                                        </div>

                                                        <div class="col-12"></div>
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <button id="newRequestButton" type="submit" class="btn btn-light"><i
                                                                        class='bx bx-user'></i>Tasdiqlash</button>
                                                            </div>
                                                            <br>
                                                            <div class="d-grid gap-2">
											                     <a href="signup.php" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i> Qaytish</a>
										                    </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <p id="message"></p>
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

<!-- //////////////////// -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function getQueryParams() {
                const params = new URLSearchParams(window.location.search);
                let queryParams = {};
                for (const [key, value] of params) {
                    queryParams[key] = decodeURIComponent(value);
                }
                return queryParams;
            }
    
            const queryParams = getQueryParams();
    
            const verificationCode = document.getElementById('inputVerificationCode').value;
    
            const formData = {
                ...queryParams,
                verification_code: verificationCode
            };
    
            async function sendData() {
                try {
                    const response = await fetch(`${local_url}/api/auth/register`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
    
                    const data = await response.json();
    
                   
                } catch (error) {
                    console.error("Ошибка при отправке запроса:", error);
                    alert("Произошла ошибка при отправке запроса. Пожалуйста, попробуйте еще раз.");
                }
            }
    
            sendData();
        });
    </script>


    <script>
        async function sendNewRequest() {
            const queryParams = new URLSearchParams(window.location.search);
            let formData = {};
            for (const [key, value] of queryParams) {
                formData[key] = decodeURIComponent(value);
            }
    
            const verificationCode = document.getElementById('inputVerificationCode').value;
            formData.otp = verificationCode;
    
            try {
                const response = await fetch(`${local_url}/api/auth/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
    
                const data = await response.json();
    
                if (response.ok) {
                    alert("Запрос успешно выполнен.");
    
                    window.location.href = 'signin.php';
                } else {
                    alert("Ошибка: " + data.error);
                }
    
            } catch (error) {
                console.error("Ошибка при отправке запроса:", error);
                alert("Произошла ошибка при отправке запроса. Пожалуйста, попробуйте еще раз.");
            }
        }
    
        document.addEventListener('DOMContentLoaded', function () {
            const newRequestButton = document.getElementById('newRequestButton');
            if (newRequestButton) {
                newRequestButton.addEventListener('click', function (event) {
                    event.preventDefault(); 
                    sendNewRequest();
                });
            }
        });
    </script>
    
</body>
</html>
