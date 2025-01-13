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
                                        <select class="form-control" id="city" name="city" required>
                                            <option value="">select city</option>
                                            <option value="andijan">andijan</option>
                                            <option value="bukhara">bukhara</option>
                                            <option value="fergana">fergana</option>
                                            <option value="jizzakh">jizzakh</option>
                                            <option value="namangan">namangan</option>
                                            <option value="navoi">navoi</option>
                                            <option value="samarkand">samarkand</option>
                                            <option value="sirdarya">sirdarya</option>
                                            <option value="surxondarya">surxondarya</option>
                                            <option value="tashkent">tashkent</option>
                                            <option value="karakalpakstan">karakalpakstan</option>
                                            <option value="khorezm">khorezm</option>
                                        </select>
                                    </div>
<!-- 
                                    <div class="form-group">
                                        <label for="room_type">Room:</label>
                                        <select class="form-control" id="room_type" name="room_type" required>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div> -->

<!-- 
                                    <div class="form-group">
                                        <label for="bed_type">Bed Type:</label>
                                        <select class="form-control" id="bed_type" name="bed_type" required>
                                            <option value="single">Single</option>
                                            <option value="double">Double</option>
                                        </select>
                                    </div> -->
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

                                    <!-- <div class="form-group">
                                        <label for="price_per_night">Price per Night:</label>
                                        <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                                    </div> -->

                                    <!-- Replace Location input with Leaflet Map -->
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
                                        <select class="form-control" id="status" >
                                            <option value="1">Available</option>
                                            <option value="0">unavailable</option>
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
                                        <div id="preview" class="mt-3"></div> <!-- Preview area for images -->
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

<script>
    const maxSize = 500 * 1024; // 500 KB в байтах

    // Инициализация карты Leaflet
    const map = L.map('locationMap').setView([41.2995, 69.2401], 13);

    // Добавление тайлов OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Маркер для выбранного местоположения
    const marker = L.marker([41.2995, 69.2401], {
        draggable: true
    }).addTo(map);

    // Обновление скрытых полей широты и долготы при перетаскивании маркера
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
        console.log('Маркер перетащен на:', position.lat, position.lng); // Вывод координат
    });

    // Установка маркера при клике на карту
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
        console.log('Местоположение выбрано на карте:', e.latlng.lat, e.latlng.lng); // Вывод координат
    });

    // Обработка изменения изображений и превью
    document.getElementById('images').addEventListener('change', function(event) {
        const preview = document.getElementById('preview');
        preview.innerHTML = ''; // Очистка предыдущего контента

        const files = event.target.files;
        if (files.length > 20) {
            alert('Вы можете загрузить максимум 20 изображений.');
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if(file.size < maxSize){
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

        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        if (!latitude || !longitude) {    
            alert('Пожалуйста, выберите местоположение на карте.');
            return;
        }

        // Создание массива для координат
        const coordinates = [parseFloat(latitude), parseFloat(longitude)];

        const formData = new FormData(document.getElementById('hotelForm'));
        console.log('form', formData)

        // Добавление массива координат в formData в виде строки JSON
        formData.append('location', JSON.stringify(coordinates)); // Отправляем массив как строку JSON
        formData.append('company_id', companyId);

        // Функция для преобразования значения в '1' или ''
        function toBooleanString(value) {
            return value === '1' ? "1" : "0";
        }

        try {
            // Преобразование значений и добавление их в formData
            const status = document.getElementById('status').value;
            // console.log((status));
            formData.append("status", toBooleanString(status));

            const breakfast = document.getElementById('breakfast').value;
            formData.append('breakfast', toBooleanString(breakfast));

            const wifi = document.getElementById('wifi').value;
            formData.append('wifi', toBooleanString(wifi));

            const airConditioner = document.getElementById('air_conditioner').value;
            formData.append('air_conditioner', toBooleanString(airConditioner));

            const kitchen = document.getElementById('kitchen').value;
            formData.append('kitchen', toBooleanString(kitchen));

            const restaurantBar = document.getElementById('restaurant_bar').value;
            formData.append('restaurant_bar', toBooleanString(restaurantBar));

            const swimmingPool = document.getElementById('swimming_pool').value;
            formData.append('swimming_pool', toBooleanString(swimmingPool));

            const gym = document.getElementById('gym').value;
            formData.append('gym', toBooleanString(gym));

            const parking = document.getElementById('parking').value;
            formData.append('parking', toBooleanString(parking));
            
        } catch (error) {
            console.error(error.message);
            return;
        }

        // Отправка данных на сервер
        fetch(`${local_url}/api/hotel/items`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${accessToken}`
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // console.log(formData);
                alert('Гостиница успешно добавлена!');
                document.getElementById('hotelForm').reset();
                preview.innerHTML = ''; // Сброс превью изображений
                marker.setLatLng([41.2995, 69.2401]); // Сброс маркера на начальные координаты
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                window.location.href = `services.php`;

            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при добавлении гостиницы.');
            });
    });



</script>

<?php include './components/footer.php' ?>