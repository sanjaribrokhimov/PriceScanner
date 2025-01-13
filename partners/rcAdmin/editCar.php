<?php include './components/header.php' ?>



<div id="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="card-header m-b-20">
                        <h4>Input Form</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="basic-form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="basic-form">
                                            <form id="carForm">
                                                <div class="form-group">
                                                    <label for="model">Model:</label>
                                                    <input type="text" class="form-control" id="model" name="model">
                                                </div>

                                                <div class="form-group">
                                                    <label for="price">Price:</label>
                                                    <input type="number" class="form-control" id="price" name="price">
                                                </div>

                                                <div class="form-group">
                                                    <label for="comment">Comment:</label>
                                                    <textarea class="form-control" id="comment" name="comment"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="color">Color:</label>
                                                    <input type="text" class="form-control" id="color" name="color">
                                                </div>

                                                <div class="form-group">
                                                    <label for="seats">Seats:</label>
                                                    <input type="number" class="form-control" id="seats" name="seats">
                                                </div>

                                                <div class="form-group">
                                                    <label for="fuel_type">Fuel Type:</label>
                                                    <select class="form-control" id="fuel_type" name="fuel_type">
                                                        <option value="">Select fuel type</option>
                                                        <option value="Petrol">Petrol</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option value="Electric">Electric</option>
                                                        <option value="Hybrid">Hybrid</option>
                                                        <option value="Ethanol">Ethanol</option>
                                                        <option value="Biodiesel">Biodiesel</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="insurance">Insurance:</label>
                                                    <select class="form-control" id="insurance" name="insurance">
                                                        <option value="">Select insurance</option>
                                                        <option value="Basic">Basic</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Full">Full</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="transmission">Transmission:</label>
                                                    <select class="form-control" id="transmission" name="transmission">
                                                        <option value="">Select transmission</option>
                                                        <option value="Manual">Manual</option>
                                                        <option value="Automatic">Automatic</option>
                                                        <option value="Semi-Automatic">Semi-Automatic</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="deposit">Deposit:</label>
                                                    <input type="number" class="form-control" id="deposit" name="deposit">
                                                </div>

                                                <div class="form-group">
                                                    <label for="year">Year:</label>
                                                    <input type="number" class="form-control" id="year" name="year">
                                                </div>

                                                <div class="form-group">
                                                    <label for="climate">Climate:</label>
                                                    <select class="form-control" id="climate" name="climate">
                                                        <option value="">Select climate</option>
                                                        <option value="air_conditioning">Air Conditioning</option>
                                                        <option value="climate_control">Climate Control</option>
                                                        <option value="Dual Zone Climate Control">Dual Zone Climate Control</option>
                                                        <option value="Multi Zone Climate Control">Multi Zone Climate Control</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="category">Category:</label>
                                                    <select class="form-control" id="category" name="category">
                                                        <option value="">Select category</option>
                                                        <option value="start">Start</option>
                                                        <option value="Comfort">comfort</option>
                                                        <option value="Electric">electro</option>
                                                        <option value="Premium">Premium</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="image">Image:</label>
                                                    <input type="file" class="form-control" id="image" name="image">
                                                    <img id="imagePreview" style="display:none; margin-top:10px;" />
                                                </div>

                                                <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                            </form>
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

<script>
    const maxSize = 500 * 1024; // 500 KB в байтах
    
    document.getElementById('saveButton').addEventListener('click', function() {
        // Получаем данные из формы
        const formData = {
            model: document.getElementById('model').value,
            price: document.getElementById('price').value,
            comment: document.getElementById('comment').value,
            color: document.getElementById('color').value,
            seats: document.getElementById('seats').value,
            fuel_type: document.getElementById('fuel_type').value,
            insurance: document.getElementById('insurance').value,
            transmission: document.getElementById('transmission').value,
            deposit: document.getElementById('deposit').value,
            year: document.getElementById('year').value,
            climate: document.getElementById('climate').value,
            category: document.getElementById('category').value
        };

        // Получаем изображение и конвертируем его в base64
        const imageInput = document.getElementById('image');
        if (imageInput.files.length > 0) {
            
            if (imageInput.files[0].size > maxSize) {
                document.getElementById('image').value = ''; // Сбрасываем выбор файла
                alert("Файл слишком большой! Пожалуйста, выберите файл меньше 500 KB.");
                return;
            }else{
                const file = imageInput.files[0];
                const reader = new FileReader();
                reader.onloadend = function() {
                    formData.image = reader.result;
                    sendApiRequest(formData);
                };
                reader.readAsDataURL(file);
                // Показать предварительный просмотр изображения
                reader.onload = function() {
                    const imgPreview = document.getElementById('imagePreview');
                    imgPreview.src = reader.result;
                    imgPreview.style.display = 'block';
                };
            }
        } else {
            sendApiRequest(formData);
        }
    });

    function sendApiRequest(formData) {
        const companyId = localStorage.getItem('company_id');
        const carId = localStorage.getItem('car_id'); // Получаем ID компании из localStorage
        const url = `${local_url}/api/rentcar/companies/${companyId}/cars/${carId}`; // URL для создания новой машины

        fetch(url, {
                method: 'PUT', // Используем POST для создания новой записи
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}` // Токен для авторизации
                },
                body: JSON.stringify(formData) // Преобразуем объект в строку JSON
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                showSuccessNotification(formData); // Показать уведомление
                setTimeout(() => {
                    window.location.href = 'services.php'; // Перенаправить на нужную страницу
                }, 3000); // Подождать 3 секунды перед перенаправлением
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при отправке данных. Пожалуйста, попробуйте еще раз.'); // Уведомление об ошибке
            });
    }

    function showSuccessNotification(formData) {
        const changes = [];
        for (const key in formData) {
            if (formData[key]) {
                changes.push(`${key.charAt(0).toUpperCase() + key.slice(1)}: ${formData[key]}`);
            }
        }
        const message = `Данные успешно отправлены:\n${changes.join('\n')}`;
        alert(message); // Простое уведомление, можно заменить на более стильный вариант
    }
</script>

<!-- JavaScript для заполнения полей формы данными из localStorage -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Извлечение данных из localStorage
        const carModel = localStorage.getItem('carModel');
        const carPrice = localStorage.getItem('carPrice');
        const carComment = localStorage.getItem('carComment');
        const carColor = localStorage.getItem('carColor');
        const carSeats = localStorage.getItem('carSeats');
        const carFuelType = localStorage.getItem('carFuelType');
        const carInsurance = localStorage.getItem('carInsurance');
        const carTransmission = localStorage.getItem('carTransmission');
        const carDeposit = localStorage.getItem('carDeposit');
        const carYear = localStorage.getItem('carYear');
        const carClimate = localStorage.getItem('carClimate');
        const carCategory = localStorage.getItem('carCategory');
        const carImage = localStorage.getItem('carImage');

        // Заполнение полей формы
        document.getElementById('model').value = carModel || '';
        document.getElementById('price').value = carPrice || '';
        document.getElementById('comment').value = carComment || '';
        document.getElementById('color').value = carColor || '';
        document.getElementById('seats').value = carSeats || '';
        document.getElementById('fuel_type').value = carFuelType || '';
        document.getElementById('insurance').value = carInsurance || '';
        document.getElementById('transmission').value = carTransmission || '';
        document.getElementById('deposit').value = carDeposit || '';
        document.getElementById('year').value = carYear || '';
        document.getElementById('climate').value = carClimate || '';
        document.getElementById('category').value = carCategory || '';

        // Отображение изображения
        if (carImage) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = carImage;
            imagePreview.style.display = 'block';
            imagePreview.style.width = '50%'; // Устанавливаем ширину изображения
            imagePreview.style.height = 'auto'; // Высота подстраивается под ширину
        }
    });
</script>



<?php include './components/footer.php' ?>