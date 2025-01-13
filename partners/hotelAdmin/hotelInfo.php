<?php include './components/header.php' ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <script>
                            var imgs = [];
                            function getQueryParams() {
                                const queryString = window.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                return Object.fromEntries(urlParams.entries());
                            }

                            const params = getQueryParams();
                            if (params.hotel_id) {
                                localStorage.setItem('hotel_id', params.hotel_id);
                                fetchHotelData(params.hotel_id);
                            }

                            function fetchHotelData(hotelId) {
                                fetch(`${local_url}/api/hotel/items/${hotelId}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Hotel not found');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        const hotel = data.hotel;
                                        imgs = hotel.images;
                                        document.getElementById('hotelName').textContent = hotel.name;
                                        document.getElementById('hotelTransport').textContent = hotel.transport;
                                        document.getElementById('hotelCity').textContent = hotel.city;
                                        document.getElementById('hotelAddress').textContent = hotel.address;
                                        document.getElementById('hotelStars').textContent = hotel.stars;
                                        document.getElementById('hotelComments').textContent = hotel.comments;

                                        // Отображение статуса
                                        document.getElementById('hotelStatus').textContent = hotel.status;

                                        const hotelImageContainer = document.getElementById('hotelImageContainer');
                                        hotelImageContainer.innerHTML = '';
                                        if (hotel.images.length > 0) {
                                            hotel.images.forEach(image => {
                                                const img = document.createElement('img');
                                                img.src = `${local_url}/api/hotel${image}`;
                                                img.className = 'img-responsive hotel-image';
                                                hotelImageContainer.appendChild(img);
                                            });
                                        } else {
                                            const img = document.createElement('img');
                                            img.src = 'default-image.jpg';
                                            img.className = 'img-responsive hotel-image';
                                            hotelImageContainer.appendChild(img);
                                        }

                                        document.querySelector('.amenities-content ul').innerHTML = `
                                            <li>WiFi: ${hotel.wifi ? 'Yes' : 'No'}</li>
                                            <li>Air Conditioning: ${hotel.air_conditioner ? 'Yes' : 'No'}</li>
                                            <li>Breakfast: ${hotel.breakfast ? 'Yes' : 'No'}</li>
                                            <li>Swimming Pool: ${hotel.swimming_pool ? 'Yes' : 'No'}</li>
                                            <li>Gym: ${hotel.gym ? 'Yes' : 'No'}</li>
                                            <li>Parking: ${hotel.parking ? 'Yes' : 'No'}</li>
                                           
                                            <li>Kitchen: ${hotel.kitchen ? 'Yes' : 'No'}</li>
                                            <li>Restaurant/Bar: ${hotel.restaurant_bar ? 'Yes' : 'No'}</li>
                                        `;

                                        const locationCoords = hotel.location.replace(/[\[\]"]/g, '').split(',');
                                        const locationDisplay = `Latitude: ${locationCoords[0]}, Longitude: ${locationCoords[1]}`;
                                        document.getElementById('hotelLocation').textContent = locationDisplay;

                                        initMap(locationCoords[0], locationCoords[1]);
                                    })
                                    .catch(error => {
                                        console.error('Error fetching hotel data:', error);
                                        alert(error.message);
                                    });
                            }

                            function initMap(lat, lng) {
                                const map = L.map('map').setView([lat, lng], 13);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '© OpenStreetMap'
                                }).addTo(map);
                                L.marker([lat, lng]).addTo(map)
                                    .bindPopup('The hotel is located here')
                                    .openPopup();
                            }

                            // function editHotel() {
                            //     alert(1)
                            //     const hotelId = localStorage.getItem('hotel_id');
                            //     if (hotelId) {
                            //         window.location.href = `editHotel.php?hotel_id=${hotelId}`;
                            //     } else {
                            //         alert('Hotel ID not found');
                            //     }
                            // }
                        </script>

                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editHotel()">
                                <i class="ti-pencil"></i>Edit
                            </button>
                        </div>

                        <br><br>

                        <div class="col-lg-8">
                            <div class="user-profile-name" id="hotelName">{name}</div>
                            <div class="contact-information">
                                <div class="city-content">
                                    <span class="contact-title">City:</span>
                                    <span id="hotelCity">{city}</span>
                                </div>
                                <div class="address-content">
                                    <span class="contact-title">Address:</span>
                                    <span id="hotelAddress">{address}</span>
                                </div>
                                <div class="stars-content">
                                    <span class="contact-title">Stars:</span>
                                    <span id="hotelStars">{stars}</span>
                                </div>
                                <div class="transport-content">
                                    <span class="contact-title">Transport:</span>
                                    <span id="hotelTransport">{transport}</span>
                                </div>
                                <div class="status-content">
                                    <span class="contact-title">Status:</span>
                                    <span id="hotelStatus">{status}</span> <!-- Новый элемент для статуса -->
                                </div>
                                <div class="location-content">
                                    <span class="contact-title">Location:</span>
                                    <span id="hotelLocation">{location}</span>
                                </div>
                                <div class="comments-content">
                                    <span class="contact-title">Comments:</span>
                                    <br>
                                    <span id="hotelComments">{comments}</span>
                                </div>
                                <div class="amenities-content">
                                    <span class="contact-title">Amenities:</span>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="map"></div>

            <div id="hotelImageContainer" class="user-photo m-b-30 "></div>
        </div>
    </div>
</div>


<script>
    function editHotel() {
        let latitude = document.getElementById('hotelLocation').textContent.split(" ")[1].split(',')[0]
        let longitude = document.getElementById('hotelLocation').textContent.split(" ")[3]
        const hotelData = {
        hotel_id: localStorage.getItem('hotel_id'),
        name: document.getElementById('hotelName').textContent,
        city: document.getElementById('hotelCity').textContent,
        address: document.getElementById('hotelAddress').textContent,
        stars: document.getElementById('hotelStars').textContent,
        transport: document.getElementById('hotelTransport').textContent,
        status: document.getElementById('hotelStatus').textContent,
        location: [latitude, longitude],
        comments: document.getElementById('hotelComments').textContent,
        amenities: Array.from(document.querySelector('.amenities-content ul').children).map(li => li.textContent),
        latitude: latitude,
        longitude: longitude,
        images: imgs,
    };

    // Сохраняем данные в localStorage
    localStorage.setItem('hotelData', JSON.stringify(hotelData));

    // Перенаправление на страницу редактирования
    if (hotelData.hotel_id) {
        window.location.href = `editHotel.php?hotel_id=${hotelData.hotel_id}`;
    } else {
        alert('Hotel ID not found');
    }
}

</script>

<?php include './components/footer.php' ?>


