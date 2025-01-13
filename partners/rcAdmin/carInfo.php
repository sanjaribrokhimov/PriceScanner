<?php include './components/header.php' ?>



<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <script>
                            // Функция для получения параметров из URL
                            function getQueryParams() {
                                const queryString = window.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                return Object.fromEntries(urlParams.entries());
                            }

                            // Сохранение car_id и company_id в localStorage
                            const params = getQueryParams();
                            if (params.car_id) {
                                localStorage.setItem('car_id', params.car_id);
                            }
                            if (params.company_id) {
                                localStorage.setItem('company_id', params.company_id);
                            }
                        </script>

                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editCar()">
                                <i class="ti-pencil"></i>Edit
                            </button>

                            <script>
                                function editCar() {
                                    const carId = localStorage.getItem('car_id');
                                    if (carId) {
                                        window.location.href = `editCar.php?car_id=${carId}`;
                                    } else {
                                        alert('Car ID not found');
                                    }
                                }
                            </script>
                        </div>

                        <br><br>

                        <div class="col-lg-4">
                            <div class="user-photo m-b-30">
                                <img class="img-responsive" src="data:image/png;base64,{image}" alt="" id="carImage" />
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="user-profile-name" id="carModel">{model}</div>
                            <div class="contact-information">
                                <div class="phone-content">
                                    <span class="contact-title">Price:</span>
                                    <span id="carPrice">{price} sum</span>
                                </div>
                                <div class="address-content">
                                    <span class="contact-title">Color:</span>
                                    <span id="carColor">{color}</span>
                                </div>
                                <div class="year-content">
                                    <span class="contact-title">Year:</span>
                                    <span id="carYear">{year}</span>
                                </div>
                                <div class="email-content">
                                    <span class="contact-title">Fuel Type:</span>
                                    <span id="carFuelType">{fuel_type}</span>
                                </div>
                                <div class="website-content">
                                    <span class="contact-title">Transmission:</span>
                                    <span id="carTransmission">{transmission}</span>
                                </div>
                                <div class="seat-content">
                                    <span class="contact-title">Seats:</span>
                                    <span id="carSeats">{seats}</span>
                                </div>
                                <div class="insurance-content">
                                    <span class="contact-title">Insurance:</span>
                                    <span id="carInsurance">{insurance}</span>
                                </div>
                                <div class="deposit-content">
                                    <span class="contact-title">Deposit:</span>
                                    <span id="carDeposit">{deposit}</span>
                                </div>
                                <div class="air-condition-content">
                                    <span class="contact-title">Climate:</span>
                                    <span id="carClimate">{climate}</span>
                                </div>
                                <div class="status-content">
                                    <span class="contact-title">Status:</span>
                                    <span id="carStatus">{status}</span>
                                </div>
                                <div class="category-content">
                                    <span class="contact-title">Category:</span>
                                    <span id="carCategory">{category}</span>
                                </div>
                                <div class="comment-content">
                                    <span class="contact-title">Comment:</span>
                                    <span id="carComment">{comment}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
</div>
</div>

<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('access_token');
        const companyId = localStorage.getItem('company_id');
        const carId = localStorage.getItem('car_id');

        if (!companyId || !carId) {
            alert('Company ID or Car ID not found in localStorage');
            return;
        }
        
        
        
        fetch(`${local_url}/api/rentcar/companies/${companyId}/cars/${carId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === true) {
                    displayCarInfo(data.car);
                } else {
                    console.error('Ошибка при получении данных о машине:', data.message);
                }
            })
            .catch(error => console.error('Ошибка:', error));



        function displayCarInfo(car) {
            // Отображение данных на странице
            document.getElementById('carModel').textContent = car.model;
            document.getElementById('carPrice').textContent = `$${car.price}`;
            document.getElementById('carColor').textContent = car.color;
            document.getElementById('carYear').textContent = car.year;
            document.getElementById('carFuelType').textContent = car.fuel_type;
            document.getElementById('carTransmission').textContent = car.transmission;
            document.getElementById('carSeats').textContent = car.seats;
            document.getElementById('carInsurance').textContent = car.insurance;
            document.getElementById('carDeposit').textContent = car.deposit;
            document.getElementById('carClimate').textContent = car.climate;
            document.getElementById('carStatus').textContent = car.status;
            document.getElementById('carCategory').textContent = car.category;
            document.getElementById('carComment').textContent = car.comment;
            // Установка изображения
            document.getElementById('carImage').src = `data:image/png;base64,${car.image}`;
        }

        function saveCarDataToLocalStorage(car) {
            localStorage.setItem('carId', car.id);
            localStorage.setItem('carModel', car.model);
            localStorage.setItem('carPrice', car.price);
            localStorage.setItem('carComment', car.comment);
            localStorage.setItem('carColor', car.color);
            localStorage.setItem('carSeats', car.seats);
            localStorage.setItem('carFuelType', car.fuel_type);
            localStorage.setItem('carInsurance', car.insurance);
            localStorage.setItem('carTransmission', car.transmission);
            localStorage.setItem('carDeposit', car.deposit);
            localStorage.setItem('carYear', car.year);
            localStorage.setItem('carClimate', car.climate);
            localStorage.setItem('carStatus', car.status);
            localStorage.setItem('carCategory', car.category);

            // Если изображение есть, сохраняем его
            if (car.image) {
                localStorage.setItem('carImage', `data:image/jpeg;base64,${car.image}`);
            } else {
                localStorage.removeItem('carImage'); // Удаляем, если изображения нет
            }
        }

        // Вызов функции после получения данных
        fetch(`${local_url}/api/rentcar/companies/${companyId}/cars/${carId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === true) {
                    displayCarInfo(data.car);
                    saveCarDataToLocalStorage(data.car); // Сохраняем данные в localStorage
                } else {
                    console.error('Ошибка при получении данных о машине:', data.message);
                }
            })
            .catch(error => console.error('Ошибка:', error));

    });
</script>


<?php include './components/footer.php' ?>