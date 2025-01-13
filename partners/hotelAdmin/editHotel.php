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
                        <div class="col-md-12">
                            <div class="basic-form">
                                <form id="hotelForm">
                                    <div class="form-group">
                                        <label for="name">Hotel Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="city">City:</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="breakfast">Breakfast:</label>
                                        <select class="form-control" id="breakfast" name="breakfast" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="wifi">WiFi:</label>
                                        <select class="form-control" id="wifi" name="wifi">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="air_conditioner">Air Conditioner:</label>
                                        <select class="form-control" id="air_conditioner" name="air_conditioner">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- Отображение карты OpenStreetMap -->
                                    <div class="form-group">
                                        <label for="locationMap">Select Location:</label>
                                        <div id="locationMap" style="height: 300px;"></div>
                                        <input type="hidden" id="latitude" name="latitude">
                                        <input type="hidden" id="longitude" name="longitude">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <input type="text" class="form-control" id="address" name="address" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="comments">Comments:</label>
                                        <textarea class="form-control" id="comments" name="comments"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="stars">Stars:</label>
                                        <select class="form-control" id="stars" name="stars" required>
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="5">5 Stars</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select class="form-control" id="status">
                                            <option value="1">Available</option>
                                            <option value="0">Unavailable</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="transport">Transport:</label>
                                        <input type="text" class="form-control" id="transport" name="transport" placeholder="e.g., Shuttle, Taxi" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="kitchen">Kitchen:</label>
                                        <select class="form-control" id="kitchen" name="kitchen">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="restaurant_bar">Restaurant/Bar:</label>
                                        <select class="form-control" id="restaurant_bar" name="restaurant_bar">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="swimming_pool">Swimming Pool:</label>
                                        <select class="form-control" id="swimming_pool" name="swimming_pool">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="gym">Gym:</label>
                                        <select class="form-control" id="gym" name="gym">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="parking">Parking:</label>
                                        <select class="form-control" id="parking" name="parking">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="images">Images (up to 20):</label>
                                        <input type="file" class="form-control" id="images" name="images" multiple accept="image/*">
                                        <div id="preview" class="mt-3"></div> <!-- Область предварительного просмотра изображений -->
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

<!-- Подключение Leaflet CSS и JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    
    const maxSize = 500 * 1024; // 500 KB в байтах

    const preview = document.getElementById('preview');
    // Инициализация карты
    let currentMarker; // Переменная для хранения текущего маркера

    const initMap = (lat, lon) => {
        const map = L.map('locationMap').setView([lat || 0, lon || 0], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Добавляем маркер для начальной позиции, если координаты заданы
        if (lat && lon) {
            currentMarker = L.marker([lat, lon]).addTo(map);
        }

        // Обработчик клика на карте
        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Удаляем предыдущий маркер, если он существует
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            // Добавляем новый маркер и сохраняем его в переменной
            currentMarker = L.marker([lat, lng]).addTo(map);
        });
    };


    // Запускаем код после загрузки страницы
    document.addEventListener('DOMContentLoaded', function () {
        
        // Получаем данные из localStorage
        const hotelData = JSON.parse(localStorage.getItem('hotelData'));

        // Проверяем, есть ли данные
        if (hotelData) {
            // Заполняем поля формы данными из hotelData
            document.getElementById('name').value = hotelData.name || '';
            document.getElementById('city').value = hotelData.city || '';
            document.getElementById('address').value = hotelData.address || '';
            document.getElementById('comments').value = hotelData.comments || '';
            document.getElementById('stars').value = hotelData.stars || '1'; // Значение по умолчанию
            document.getElementById('transport').value = hotelData.transport || '';
            document.getElementById('status').value = hotelData.status === '1' ? '1' : '0'; // Поскольку статус может быть строкой

            preview.innerHTML = ''; // Очищаем предыдущий предпросмотр
            console.log(hotelData.images)
            if (hotelData.images.length > 0) {
                hotelData.images.forEach(image => {
                    const img = document.createElement('img');
                    img.src = `${local_url}/api/hotel${image}`;
                    img.className = 'img-responsive hotel-image';
                    img.style.width = '100px'; // Установка ширины изображения
                    img.style.marginRight = '10px'; // Отступ между изображениями
                    preview.appendChild(img);
                    console.log(img)
                });
            } else {
                const img = document.createElement('img');
                img.src = 'default-image.jpg';
                img.className = 'img-responsive hotel-image';
                preview.appendChild(img);
            }

            // Заполнение значений для amenities
            const amenities = hotelData.amenities || [];
            document.getElementById('wifi').value = amenities.includes('WiFi: Yes') ? '1' : '0';
            document.getElementById('air_conditioner').value = amenities.includes('Air Conditioning: Yes') ? '1' : '0';
            document.getElementById('breakfast').value = amenities.includes('Breakfast: Yes') ? '1' : '0';
            document.getElementById('kitchen').value = amenities.includes('Kitchen: Yes') ? '1' : '0';
            document.getElementById('restaurant_bar').value = amenities.includes('Restaurant/Bar: Yes') ? '1' : '0';
            document.getElementById('swimming_pool').value = amenities.includes('Swimming Pool: Yes') ? '1' : '0';
            document.getElementById('gym').value = amenities.includes('Gym: Yes') ? '1' : '0';
            document.getElementById('parking').value = amenities.includes('Parking: Yes') ? '1' : '0';

            // Заполняем местоположение
            document.getElementById('latitude').value = hotelData.latitude || '';
            document.getElementById('longitude').value = hotelData.longitude || '';
            initMap(hotelData.latitude, hotelData.longitude);
            console.log('initMap')
        }

        


        // Обработчик предварительного просмотра изображений
        document.getElementById('images').addEventListener('change', function (event) {
            const files = event.target.files;
            // Очищаем предыдущий предварительный просмотр
            preview.innerHTML = ''; 

            if (files.length > 20) {
                alert('Вы можете загрузить максимум 20 изображений.');
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if(file.size < maxSize){
                    // Проверка типа файла
                    if (!file.type.startsWith('image/')) {
                        alert('Пожалуйста, выберите изображения.');
                        continue; // Пропустить файл, если это не изображение
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px'; // Установка ширины изображения
                        img.style.marginRight = '10px'; // Отступ между изображениями
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }else{
                    document.getElementById('image').value = ''; // Сбрасываем выбор файла
                    alert("Файл слишком большой! Пожалуйста, выберите файл меньше 500 KB.");
                    return;
                }
                
            }
        });



        document.getElementById('saveButton').addEventListener('click', function() {
            const accessToken = localStorage.getItem('access_token');
            const companyId = localStorage.getItem('company_id');
            const urlParams = new URLSearchParams(window.location.search);
            const hotelId = urlParams.get('hotel_id');

            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;

            if (!latitude || !longitude) {    
                alert('Пожалуйста, выберите местоположение на карте.');
                return;
            }

            const coordinates = [parseFloat(latitude), parseFloat(longitude)];
            const formData = new FormData(document.getElementById('hotelForm'));

            formData.append('location', JSON.stringify(coordinates));
            formData.append('company_id', companyId);

            // Преобразование значений для удобств
            function toBooleanString(value) {
                return value === '1' ? "1" : "0";
            }

            const status = document.getElementById('status').value;
            formData.append("status", toBooleanString(status));
            formData.append('breakfast', toBooleanString(document.getElementById('breakfast').value));
            formData.append('wifi', toBooleanString(document.getElementById('wifi').value));
            formData.append('air_conditioner', toBooleanString(document.getElementById('air_conditioner').value));
            formData.append('kitchen', toBooleanString(document.getElementById('kitchen').value));
            formData.append('restaurant_bar', toBooleanString(document.getElementById('restaurant_bar').value));
            formData.append('swimming_pool', toBooleanString(document.getElementById('swimming_pool').value));
            formData.append('gym', toBooleanString(document.getElementById('gym').value));
            formData.append('parking', toBooleanString(document.getElementById('parking').value));

            // Проверяем, выбраны ли новые изображения
            const imageInput = document.getElementById('images');
            formData.images = null;
            if (imageInput.files.length <= 0) {
                formData.delete('images');
            }

            fetch(`${local_url}/api/hotel/items/${hotelId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${accessToken}`
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при сохранении данных на сервере.');
                    }
                    return response.headers.get('Content-Length') !== '0' ? response.json() : {};
                })
                .then(data => {
                    alert('Данные успешно сохранены!');
                    document.getElementById('hotelForm').reset();
                    preview.innerHTML = ''; 
                    currentMarker.setLatLng([41.2995, 69.2401]); 
                    document.getElementById('latitude').value = '';
                    document.getElementById('longitude').value = '';
                    window.location.href = `hotelInfo.php?hotel_id=${hotelId}`;
                })
                .catch(error => {
                    console.warn('Ошибка:', error);
                    alert('Произошла ошибка при добавлении гостиницы.');
                });
        });

    });
</script>

<?php include './components/footer.php' ?>
