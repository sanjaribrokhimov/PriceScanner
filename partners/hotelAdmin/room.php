<?php include './components/header.php' ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <script>
                        </script>
<!-- 
                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editHotel()">
                                <i class="ti-pencil"></i>Edit
                            </button>
                        </div> -->

                        <br><br>

                        
                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editRoom()">
                                <i class="ti-pencil"></i>Edit
                            </button>
                        </div>

                        <div class="col-lg-8">
                            <div class="contact-information">
                                <div class="city-content">
                                    <span class="contact-title">Status:</span>
                                    <span id="is_available_info">{status}</span>
                                </div>
                                <div class="city-content">
                                    <span class="contact-title">Room type:</span>
                                    <span id="room_type_info">{room_type}</span>
                                </div>
                                <div class="status-content">
                                    <span class="contact-title">Bed type:</span>
                                    <span id="bed_type_info">{bed_type}</span>
                                </div>
                                <div class="status-content">
                                    <span class="contact-title">Capacity:</span>
                                    <span id="capacity_info">{Capacity}</span>
                                </div>
                                <div class="comments-content">
                                    <span class="contact-title">Features:</span>
                                    <span id="features_info">{features}</span>
                                </div>
                                <div class="status-content">
                                    <span class="contact-title">Price:</span>
                                    <span id="price_per_night_info">{price}</span>
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

    var roomData;
    var roomId;
    var hotel_id;
    const modal = document.getElementById('customModal');
    const closeBtn = modal.querySelector('.close');
    // Close Modal
    function closeModal() {
        modal.classList.remove('show');
    }

    // Close on clicking close button or outside modal content
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    
    function getQueryParams() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        return Object.fromEntries(urlParams.entries());
    }

    const params = getQueryParams();
    if (params.id) {
        localStorage.setItem('room_id', params.id);
        roomId = params.id;
        fetchRoomData();
    }

    function fetchRoomData() {
        fetch(`${local_url}/api/hotel/room/${roomId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Room not found');
                }
                return response.json();
            })
            .then(data => {
                const room = data.room;
                document.getElementById('room_type_info').textContent = room.room_type;
                document.getElementById('bed_type_info').textContent = room.bed_type;
                document.getElementById('capacity_info').textContent = room.capacity;
                document.getElementById('features_info').textContent = room.features;
                document.getElementById('price_per_night_info').textContent = room.price_per_night;
                document.getElementById('is_available_info').innerHTML = `<mark style="background: #0f0;">${room.is_available ? "Active" : "Unactive"}</mark>`;
                roomData = data.room;
                hotel_id = room.hotel_id;
            })
            .catch(error => {
                console.error('Error fetching room data:', error);
                alert(error.message);
            });
    }

    
    function editRoom() {
        
        modal.classList.add('show')
        document.querySelectorAll('#addRoomForm .form-group')[7].style.display = "none";
        const form = document.getElementById('addRoomForm');
        document.getElementById('room_type').value = roomData.room_type;
        document.getElementById('bed_type').value = roomData.bed_type;
        document.getElementById('is_available').value = roomData.is_available;
        document.getElementById('capacity').value = roomData.capacity;
        document.getElementById('features').value = roomData.features;
        document.getElementById('price_per_night').value = roomData.price_per_night;
        // Handle form submission
        form.addEventListener('submit', (event) => {
            var apiForRoom = `${local_url}/api/hotel/rooms/bulk`;
        
            const num_adults = document.getElementById('num_adults').value.trim();
            const capacity = document.getElementById('capacity').value.trim();
            if(+num_adults < +capacity){
                alert("Adults < capacity");
                document.getElementById('num_adults').style.borderColor = "#f00";
                document.getElementById('capacity').style.borderColor = "#f00";
                return;
            }

            const form = document.getElementById('addRoomForm');
            const formData = new FormData(form);
            formData.append('hotel_id', hotel_id);

            const quantity = document.getElementById('quantity');
            // console.log('Room Data:', Object.fromEntries(formData.entries()));
            if(+quantity.value == 1){
                apiForRoom = `${local_url}/api/hotel/room`;
                formData.delete('quantity')
            }
            // console.log(apiForRoom);
            // console.log('Room Data:', Object.fromEntries(formData.entries()));
            const data = {};
            for (let [key, value] of formData.entries()) {
                // Если ключ ожидает числовое значение, преобразуем его
                if (key === 'num_adults' || key === 'price_per_night' || key === "hotel_id" || key === "capacity" || key === "quantity") {
                    data[key] = parseFloat(value); // Преобразуем в число с плавающей точкой
                }else if(key === "is_available"){
                    data[key] = value === "true" ? true : false;
                }else {
                    data[key] = value; // Сохраняем как есть
                }
            }
            console.log(data)


            // Отправка данных на сервер
            fetch(`${local_url}/api/hotel/room/${roomId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.access_token}`
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(data => {
                    // console.log(formData);
                    alert('Комната успешно добавлена!');
                    // Reset form and close modal
                    form.reset();
                    closeModal();
                    if(confirm("Обновить страницу?")){
                        window.location.reload();
                    }

                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при добавлении гостиницы.');
                });
        });

    }

</script>

<?php include './components/footer.php' ?>


