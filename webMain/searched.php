<?php include 'components/header.php'; ?>

<style>
    .card-img-top
    {
        height: 200px;
    }
    #products-container div.col
    {
        padding-bottom: 10px;
        padding-top: 10px;
    }
    .page-item
    {
        list-style: none;
    }
    
</style>

<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-12" id="product-display">
                <div class="product-wrapper">
                    <div class="product-grid">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3" id="products-container">
                            <!-- Продукты будут отрисованы здесь -->
                        </div>
                    </div>
                    <hr>
                    <nav class="d-flex justify-content-between" aria-label="Page navigation" id="pagination-container">
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    document.querySelector('#query').value = query;
    document.querySelector('#category').value = category;

    // Проверяем значение
    if (!query) {
        console.log("Параметр query отсутствует в URL.");
    } else {
        
        async function searchData(queryVal){

            const url = `${local_url}/api/search/?query=${queryVal}`;
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    cannotRender();
                    console.error("Не удалось получить данные:", response.status);
                    return;
                }

                const data = await response.json();

                renderData(data)

                cars = data.car;
                tours = data.tour;
                hotels = data.hotel;
            } catch (error) {
                console.error("Ошибка при получении данных:", error);
            }
        }
        searchData(query)     
    }
    function renderData(data) {
        const productsContainer = document.getElementById("products-container");
        productsContainer.innerHTML = ""; // Очищаем предыдущие результаты

        // Функция для отрисовки данных по выбранному типу
        const renderByType = (type) => {
            productsContainer.innerHTML = ""; // Очистка контейнера
            const items = data[type]; // Получаем данные для выбранного типа

            if (!items || items.length === 0) {
                productsContainer.innerHTML = `<p>“${type}” toifasi uchun maʼlumotlar mavjud emas.</p>`;
                return;
            }

            items.forEach(item => {
                
                let isLiked = `bx-heart`;
                liked.forEach(like => {
                    if(like.product_type === type && like.product_company_id === item.company_id && like.product_id === item.id){
                        isLiked = `bxs-heart`;
                    }
                })
                let itemHTML = "";
                // console.log(item)
                if (type === "hotel") {
                    itemHTML = `
                        <div class="col-md-3" style="margin-bottom: 15px">
                            <div class="card rounded-0 product-card">
                                <div class="card-header bg-transparent border-bottom-0">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:;">
                                        </a>
                                        <a href="javascript:;">
                                            <div class="product-wishlist" onclick="toLike(${item.id}, ${item.company_id}, 'hotel', this)"><i class='bx ${isLiked}'></i></div>
                                        </a>
                                    </div>
                                </div>
                                <a href="javascript:;">
                                    <img src="${local_url}/api/hotel${item.images[0]}" 
                                        class="card-img-top" 
                                        alt="Hotel image" 
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <div class="product-info">
                                        <a href="javascript:;">
                                            <h6 class="product-name mb-2">${item.name}</h6>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <div class="mb-1 product-price">
                                                <span class="text-white fs-5">${item.price_per_night} sum</span>
                                            </div>
                                            <div class="cursor-pointer ms-auto">
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                            </div>
                                        </div>                                        
                                        <div class="product-action mt-2">
                                            <div class="d-grid gap-2">
                                                <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toHotels()" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"><i class='bx bxs-cart-add'></i> Buyurtma Berish</a>
                                                <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
                                                onclick="showHotelDetails('${item.name}', '${item.address}', ${item.stars}, '${item.comments}', '${item.location}', '${item.wifi}', '${item.breakfast}', '${item.gym}', '${item.swimming_pool}', '${item.parking}', '${item.restaurant_bar}', '${item.images}')">
                                                <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                } else if (type === "car") {
                            
                    // Случайно уменьшаем цену (не ниже 0)
                    const discountedPrice = Math.max(item.price - Math.floor(Math.random() * 20), 0);

                    itemHTML = `
                        <div class="col-md-3">
                            <div class="card rounded-0 product-card">
                                <div class="card-header bg-transparent border-bottom-0">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <a href="javascript:;">
                                        </a>
                                        <a href="javascript:;">
                                            <div class="product-wishlist"><i class='bx bx-heart'></i></div>
                                        </a>
                                    </div>
                                </div>
                                <a href="javascript:;">
                                    <img src="data:image/png;base64,${item.image}" 
                                        class="card-img-top" 
                                        alt="Car image" 
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <div class="product-info">
                                        <a href="javascript:;">
                                            <p class="product-category font-13 mb-1">${item.company.name}</p>
                                        </a>
                                        <a href="javascript:;">
                                            <h6 class="product-name mb-2">${item.model}</h6>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <div class="mb-1 product-price">
                                                <span class="me-1 text-decoration-line-through">$${discountedPrice}.00</span>
                                                <span class="text-white fs-5">$${item.price}</span>
                                            </div>
                                            <div class="cursor-pointer ms-auto">
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i>
                                            </div>
                                        </div>
                                        <div class="product-action mt-2">
                                            <div class="d-grid gap-2">
                                                <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${item.id}\`, 'car')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"><i class='bx bxs-cart-add'></i> Buyurtma Berish</a>
                                                <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
        onclick="showCarDetails(\`${item.model}\`, \`${item.price}\`, \`${item.company.name}\`, \`${item.image}\`, \`${item.color}\`, \`${item.year}\`, \`${item.seats}\`, \`${item.fuel_type}\`, \`${item.transmission}\`, \`${item.deposit}\`, \`${item.insurance}\`, \`${item.comment}\`)">
        <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
        </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                } else if (type === "tour") {
                    
                // Находим минимальную цену среди всех выездов
                const lowestPrice = Math.min(...item.departures.map(d => d.price));
                // console.log(lowestPrice)
                    itemHTML = `
                        <div class="col">
                            <div class="card rounded-0 product-card">
                                <img src="${local_url}/api/tour${item.images[0]}" class="card-img-top" alt="${item.title}">
                                <div class="card-body">
                                    <h6 class="product-name mb-2">${item.title}</h6>
                                    <div class="product-price"><span>$${lowestPrice}</span></div>
                                    <a href="javascript:;" class="btn btn-light btn-ecomm col-12" onclick="toCart(\`${item.id}\`, 'tour')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct">Tadqiq qiling</a>
                                    <a href="javascript:;" onclick="showTourDetails(${item.id})" data-bs-toggle="modal" data-bs-target="#QuickViewProduct" class="btn btn-ecomm col-12">Batafsil</a>
                                </div>
                            </div>
                        </div>`;
                }
                productsContainer.insertAdjacentHTML("beforeend", itemHTML);
            });
        };
    
        // Отрисовываем данные для типа по умолчанию
        renderByType(category);
    }

    
    // function showHotelDetails(name, price, address, stars, comments, location, roomType, wifi, breakfast, gym, swimmingPool, parking, restaurantBar, images) {
    //     images = images.split(',')
    //     const tf = {
    //         'false': 'Yo\'q', 
    //         'true': 'Mavjud'
    //     }
    //     location = JSON.parse(location);
    //     const latitude = location[0] > 90 ? location[0]%90 : location[0];
    //     const longitude = location[1] > 180 ? location[1]%180 : location[1];
    //     const googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
    //     const yandexMapLink = `https://yandex.ru/maps/?ll=${longitude},${latitude}&z=14&l=map`;
    //     let comment = ''
    //     comments.split(',').forEach(e => {
    //         comment += `<p>${e}</p>`
    //     })
    //     // alert('hotel')
    //     // Устанавливаем значения в модальное окно
    //     document.getElementById('modalHotelModel').textContent = name;
    //     document.getElementById('modalHotelPrice').textContent = `Narx: ${price} sum`;
    //     document.getElementById('modalHotelAddress').textContent = `Manzil: ${address}`;
    //     document.getElementById('modalHotelStars').textContent = '⭐'.repeat(stars);
    //     document.getElementById('modalHotelComments').innerHTML = `${comment}`;
    //     document.getElementById('modalHotelLocation').innerHTML = `<a href="${googleMapsUrl}" style="color: #3f8dff" target="_blank">Google Maps</a>`;
    //     //<a href="${yandexMapLink}" target="_blank">Yandex Maps</a>
    //     document.getElementById('modalHotelRoomType').textContent = `${roomType}`;
    //     document.getElementById('modalHotelWifi').textContent = tf[wifi];
    //     document.getElementById('modalHotelBreakfast').textContent = tf[breakfast];
    //     document.getElementById('modalHotelGym').textContent = tf[gym];
    //     document.getElementById('modalHotelSwimmingPool').textContent = tf[swimmingPool];
    //     document.getElementById('modalHotelParking').textContent = tf[parking];
    //     document.getElementById('modalHotelRestaurantBar').textContent = tf[restaurantBar];

    //     const gallery = $('.product-gallery');
    //     gallery.trigger('destroy.owl.carousel'); // Уничтожаем карусель
    //     gallery.find('.owl-stage-outer').children().unwrap(); // Убираем обертку

    //     // Устанавливаем новое изображение
    //     let carouselResult = ''
    //     images.forEach(e => {
    //         carouselResult += `
    //             <div class="item">
    //                 <img id="modalCarImage" class="img-fluid" src="${local_url}/api/hotel/${e}" alt="Tasvir">
    //             </div>
    //         `
    //     })
    //     gallery.html(carouselResult);

    //     // Инициализируем карусель заново
    //     gallery.owlCarousel({
    //         items: 1,
    //         loop: true,
    //         nav: true,
    //         dots: true,
    //     });
    // }

    
    // function showCarDetails(model, price, companyName, image, color, year, seats, fuelType, transmission, deposit, insurance, comment) {
    //     document.getElementById('modalToRender').innerHTML = carModalBody
    // // Устанавливаем значения в модальное окно
    //     document.getElementById('modalCarModel').textContent = model;
    //     document.getElementById('modalCarPrice').textContent = `Narx: $${price}`;
    //     document.getElementById('modalCarCompany').textContent = companyName;
    //     document.getElementById('modalCarColor').textContent = color;
    //     document.getElementById('modalCarYear').textContent = year;
    //     document.getElementById('modalCarSeats').textContent = seats;
    //     document.getElementById('modalCarFuel').textContent = fuelType;
    //     document.getElementById('modalCarTransmission').textContent = transmission;
    //     document.getElementById('modalCarDeposit').textContent = deposit;
    //     document.getElementById('modalCarInsurance').textContent = insurance;
    //     document.getElementById('modalCarComment').textContent = comment;

    

    //     // Очищаем и обновляем карусель изображений
    //     const gallery = $('.product-gallery');
    //     gallery.trigger('destroy.owl.carousel'); // Уничтожаем карусель
    //     gallery.find('.owl-stage-outer').children().unwrap(); // Убираем обертку

    //     // Устанавливаем новое изображение
    //     gallery.html(`
    //         <div class="item">
    //             <img id="modalCarImage" class="img-fluid" src="data:image/png;base64,${image}" alt="Tasvir">
    //         </div>
    //     `);

    //     // Инициализируем карусель заново
    //     gallery.owlCarousel({
    //         items: 1, // Выводим одно изображение
    //         loop: true, // Зацикливание карусели
    //         nav: true, // Кнопки навигации
    //         dots: false // Точки внизу
    //     });
    // }


    
    // function showTourDetails(tourId) {
    //     const tour = tours.find(item => item.id === tourId);
    //     if (tour) {
    //         document.getElementById('modalToRender').innerHTML = tourModalBody;

    //         // Обновляем заголовок тура
    //         document.getElementById('modalTourTitle').textContent = tour.title;

    //         // Добавляем Sanalar va narxlar
    //         let departuresHTML = '<h5>Sanalar va narxlar</h5>';
    //         console.log(tour)
    //         tour.departures.forEach(departure => {
    //             const formattedDate = departure.date.replace('T00:00:00', ' ');
    //             departuresHTML += `
    //                 <div class="departure-item mb-3">
    //                     <div class="d-flex justify-content-between align-items-center">
    //                         <span class="departure-date text-light fs-6">${formattedDate.trim()}</span>
    //                         <span class="departure-price text-success fs-5 fw-bold">$${departure.price}</span>
    //                     </div>
    //                 </div>
    //             `;
    //         });
    //         document.getElementById('modalTourDepartures').innerHTML = departuresHTML;

    //         // Описание тура
    //         document.getElementById('modalTourDescription').textContent = tour.description;

    //         // Добавляем информацию о категории, стране отправления и стране назначения
    //         const categoryHTML = `
    //             <div class="tour-details mt-3">
    //                 <p class="text-light fs-6">kategoriya: <span class="text-info">${tour.category}</span></p>
    //                 <p class="text-light fs-6">dan: <span class="text-info">${tour.from_country}</span></p>
    //                 <p class="text-light fs-6">ga: <span class="text-info">${tour.to_country}</span></p>
    //             </div>
    //         `;
    //         document.getElementById('modalTourDescription').insertAdjacentHTML('beforeend', categoryHTML);

    //         // Добавляем изображения тура
    //         const gallery = $('.product-gallery');
    //         gallery.trigger('destroy.owl.carousel'); // Уничтожаем карусель
    //         gallery.find('.owl-stage-outer').children().unwrap(); // Убираем обертку

    //         // Устанавливаем новое изображение
    //         let carouselResult = ''
    //         tour.images.forEach(e => {
    //             carouselResult += `
    //                 <div class="item">
    //                     <img id="modalCarImage" class="img-fluid" src="${local_url}/api/tour/${e}" alt="Tasvir">
    //                 </div>
    //             `
    //         })
    //         gallery.html(carouselResult);

    //         // Инициализируем карусель заново
    //         gallery.owlCarousel({
    //             items: 1,
    //             loop: true,
    //             nav: true,
    //             dots: true,
    //         });

    //         // Добавляем видео
    //         // let originalVideoUrl = tour.video_url;
    //         // let videoIdMatch = originalVideoUrl?.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|.+\?v=))([^&]+)/);
    //         // let videoId = videoIdMatch ? videoIdMatch[1] : null;

    //         // if (videoId) {
    //         //     let clean_video_url = `https://www.youtube.com/embed/${videoId}`;
    //         //     document.getElementById('modalTourVideo').innerHTML = `<iframe width="100%" height="400" src="${clean_video_url}" frameborder="0" allowfullscreen></iframe>`;
    //         // }

    //         // Обновляем цену
    //         updatePrice(tour.departures[0].price);
    //     }
    // }


    function updatePrice(price) {
        document.getElementById('modalTourPrice').textContent = `$${price}`;
    }



</script>


<!-- Модальное окно для отображения подробной информации об отеле -->
<div class="modal fade" id="QuickViewProductHotel">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down">
        <div class="modal-content bg-dark-4 rounded-0 border-0">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                <div class="row g-0">
                    <div class="col-12 col-lg-6">
                        <div class="image-zoom-section">
                            <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                                <div class="item">
                                    <img id="modalCarImage" class="img-fluid" alt="Tasvir">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="product-info-section p-3 hotelModal">
                            <h3 style="color:aliceblue;" class="mt-3 mt-lg-0 mb-0" id="modalHotelModel">error</h3>
                            <div class="d-flex align-items-center mt-3 gap-2">
                                <h4 style="color: green;" class="mb-0" id="modalHotelPrice">error</h4>
                            </div>
                            <div class="mt-3">
                                <h6 id="modalHotelAddress">Manzil: error</h6>
                            </div>
                            <dl class="row mt-3">
                                <dt class="col-sm-3">Yulduzlar</dt>
                                <dd class="col-sm-9" id="modalHotelStars">error</dd>
                                <dt class="col-sm-3">Joylashuv</dt>
                                <dd class="col-sm-9" id="modalHotelLocation">error</dd>
                                <dt class="col-sm-3">Xona turi</dt>
                                <dd class="col-sm-9" id="modalHotelRoomType">error</dd>
                                <dt class="col-sm-3">Wi-Fi</dt>
                                <dd class="col-sm-9" id="modalHotelWifi">error</dd>
                                <dt class="col-sm-3">Nonushta</dt>
                                <dd class="col-sm-9" id="modalHotelBreakfast">error</dd>
                                <dt class="col-sm-3">Sport zali</dt>
                                <dd class="col-sm-9" id="modalHotelGym">error</dd>
                                <dt class="col-sm-3">Suv havzasi</dt>
                                <dd class="col-sm-9" id="modalHotelSwimmingPool">error</dd>
                                <dt class="col-sm-3">Avtomobil to'xtatish</dt>
                                <dd class="col-sm-9" id="modalHotelParking">error</dd>
                                <dt class="col-sm-3">Restoran/Ba'z</dt>
                                <dd class="col-sm-9" id="modalHotelRestaurantBar">error</dd>
                                <dt class="col-sm-3">Izohlar</dt>
                                <dd class="col-sm-9" id="modalHotelComments">error</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Модальное окно для отображения подробной информации о машине -->
<div class="modal fade carModal" id="QuickViewProductCar">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down">
        <div class="modal-content bg-dark-4 rounded-0 border-0">
            <div class="modal-body">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                <div class="row g-0">
                    <div class="col-12 col-lg-6">
                        <div class="image-zoom-section">
                            <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                                <div class="item">
                                    <img id="modalCarImage" class="img-fluid" alt="Tasvir">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="product-info-section p-3">
                            <h3 style="color:aliceblue;" class="mt-3 mt-lg-0 mb-0" id="modalCarModel">error</h3>
                            <div class="d-flex align-items-center mt-3 gap-2">
                                <h4 style="color: green;" class="mb-0" id="modalCarPrice">error</h4>
                            </div>
                            <div class="mt-3">
                                <h6 id="modalCarCompany">Kompaniya</h6>
                                
                            </div>
                            <dl class="row mt-3">
                                <dt class="col-sm-3">Rang</dt>
                                <dd class="col-sm-9" id="modalCarColor">error</dd>
                                <dt class="col-sm-3">Yili</dt>
                                <dd class="col-sm-9" id="modalCarYear">error</dd>
                                <dt class="col-sm-3">O'rindiqlar</dt>
                                <dd class="col-sm-9" id="modalCarSeats">error</dd>
                                <dt class="col-sm-3">Yonilg'i turi</dt>
                                <dd class="col-sm-9" id="modalCarFuel">error</dd>
                                <dt class="col-sm-3">uzatma qutisi</dt>
                                <dd class="col-sm-9" id="modalCarTransmission">error</dd>
                                <dt class="col-sm-3">Depozit</dt>
                                <dd class="col-sm-9" id="modalCarDeposit">error</dd>
                                <dt class="col-sm-3">Sug'urta</dt>
                                <dd class="col-sm-9" id="modalCarInsurance">error</dd>
                                <dt class="col-sm-3">Commentariya</dt>
                                <dd class="col-sm-9" id="modalCarComment">error</dd>
                                
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal oynasi -->



<?php include 'components/footer.php'; ?>
