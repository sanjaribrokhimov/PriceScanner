<script src="js/productDetails.js"></script>
<script>

async function fetchHotels(page = 1) {
    try {
        const response = await fetch(`${local_url}/api/hotel/items?page=${page}`);
        const data = await response.json();
        // console.log('asdasdasd', data)

        if (data.hotels) {
            const randomHotels = getRandomHotels(data.hotels, 4);
            // console.log(randomHotels);
            hotels = data.hotels;
            await renderHotels(randomHotels);  // Ждём завершения рендеринга
        }
    } catch (error) {
        console.error('Mehmonxonalarni olishda xatolik yuz berdi:', error);
    }
}

function getRandomHotels(hotels, count) {
    const shuffled = hotels.sort(() => 0.5 - Math.random()); // Массовая случайная сортировка
    return shuffled.slice(0, count); // Даем случайные 8 отелей
}

async function renderHotels(hotels) {
    const hotelList = document.getElementById('hotel-list');
    hotelList.innerHTML = ''; // Очистка предыдущего контента

    let row;
    
    for (let index = 0; index < hotels.length; index++) {
        const hotel = hotels[index];

        let isLiked = `bx-heart`;
        liked.forEach(like => {
            if(like.product_type === "hotel" && like.product_company_id === hotel.company_id && like.product_id === hotel.hotel_id){
                isLiked = `bxs-heart`;
            }
        })

        if (index % 4 === 0) {
            row = document.createElement('div');
            row.className = 'row';
            hotelList.appendChild(row);
        }
        
        if (hotel) {
            // Вызываем рендеринг HTML для этого отеля
            const hotelHTML = generateHotelHTML(hotel, isLiked);
            row.innerHTML += hotelHTML;
        }
    }
}


function generateHotelHTML(hotel, isLiked) {
    hotel.comments = hotel.comments.split("\r\n");
    // console.log(room)
    // console.log(hotel)

    return `
        <div class="col-md-3">
            <div class="card rounded-0 product-card">
                <div class="card-header bg-transparent border-bottom-0">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="javascript:;"></a>
                        <a href="javascript:;">
                            <div class="product-wishlist" onclick="toLike(${hotel.hotel_id}, ${hotel.company_id}, 'hotel', this)"><i class='bx ${isLiked}'></i></div>
                        </a>
                    </div>
                </div>
                <a href="javascript:;">
                    <img src="${local_url}/api/hotel${hotel.images[0]}" 
                         class="card-img-top" 
                         alt="Mehmonxona rasmi" 
                         style="width: 100%; height: 200px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <div class="product-info">
                        <a href="javascript:;">
                            <h6 class="product-name mb-2">${hotel.name}</h6>
                        </a>
                        <div class="d-flex align-items-center">
                            <div class="cursor-pointer ms-auto stars-count">
                            </div>
                        </div>
                        <div class="product-action mt-2">
                            <div class="d-grid gap-2">
                                <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toHotels()">
                                    <i class='bx bxs-cart-add'></i> Buyurtma Berish
                                </a>
                                <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
                                   onclick="showHotelDetails('${hotel.name}', '${hotel.address}', ${hotel.stars}, '${hotel.comments}', '${hotel.location}', '${hotel.wifi}', '${hotel.breakfast}', '${hotel.gym}', '${hotel.swimming_pool}', '${hotel.parking}', '${hotel.restaurant_bar}', '${hotel.images}')">
                                   <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

</script>

<!-- Контейнер для списка отелей -->
<div id="hotel-list"></div>

