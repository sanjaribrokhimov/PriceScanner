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
    
</style>

<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="search-block" class="mb-4">
                    <div class="row gap-3 gap-md-0">
                        <div class="col-md-2">
                            <select id="filter_city" name="filter_city" class="form-select" style="background-color: transparent;border-color:rgba(255 255 255 / .5);">
                                <option value="">Hududni tanlang</option>
                                <option value="andijan">Andijon</option>
                                <option value="bukhara">Buxoro</option>
                                <option value="fergana">Farg'ona</option>
                                <option value="jizzakh">Jizzax</option>
                                <option value="namangan">Namangan</option>
                                <option value="navoi">Navoiy</option>
                                <option value="samarkand">Samarqand</option>
                                <option value="sirdarya">Sirdaryo</option>
                                <option value="surxondarya">Surxondaryo</option>
                                <option value="tashkent">Toshkent</option>
                                <option value="karakalpakstan">Qoraqalpog'iston</option>
                                <option value="khorezm">Xorazm</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group filter shadow-in-input m-0">
                            <input id="filter_num_adults" type="number" class="form-control" placeholder="Kattalar soni">
                        </div>
                        <div class="col-md-2 form-group filter shadow-in-input m-0">
                            <input id="filter_num_rooms" type="number" class="form-control" placeholder="Hona soni">
                        </div>
                        <div class="col-md-2">
                            <select id="filter_bed_type" class="form-select" style="background-color: transparent;border-color:rgba(255 255 255 / .5);">
                                <option value="">Yotoq turi</option>
                                <option value="queen">Queen</option>
                                <option value="king">King</option>
                                <option value="twin">Twin</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group filter shadow-in-input m-0">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" style="background-color: transparent;border-color:rgba(255 255 255 / .5);">
                                    Dates
                                </button>
                                <form class="dropdown-menu p-4" style="width: max-content;background-color: rgb(48 114 240);box-shadow:0 0 10px 5px rgba(0 0 0 / .1)">
                                    <div class="mb-3">
                                        <label for="filter_start_date" class="form-label">Start date</label>
                                        <input type="date" class="form-control" id="filter_start_date" placeholder="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="filter_end_date" class="form-label">Password</label>
                                        <input type="date" class="form-control" id="filter_end_date" placeholder="">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="search-button" class="btn btn-primary">Qidirish</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtrlar va mahsulotlar standart ravishda yashirilgan -->
            <div class="col-12 col-xl-3 d-none" id="filter-sidebar">
                <div class="btn-mobile-filter d-xl-none"><i class='bx bx-slider-alt'></i></div>
                <div class="filter-sidebar d-xl-flex" style="z-index: 10000">
                    <div class="card rounded-0 w-100">
                        <div class="card-body">
                            <div class="align-items-center d-flex d-xl-none">
                                <h6 class="text-uppercase mb-0">Filter</h6>
                                <div class="btn-mobile-filter-close btn-close ms-auto cursor-pointer"></div>
                            </div>
                            <hr class="d-flex d-xl-none" />
                            <div class="price-range">
                                <h6 class="text-uppercase mb-3">Narx</h6>
                                <div class="d-flex align-items-center mb-3">

                                    <div class="wrapper">
                                        <div class="price-input">
                                            <div class="field">
                                                <span>Min</span>
                                                <input type="number" class="input-min" value="0" disabled>
                                            </div>
                                            <div class="separator">-</div>
                                            <div class="field">
                                                <span>Max</span>
                                                <input type="number" class="input-max" value="10000000" disabled>
                                            </div>
                                        </div>
                                        <div class="slider">
                                            <div class="progress"></div>
                                        </div>
                                        <div class="range-input">
                                            <input type="range" class="range-min" min="0" max="10000000" value="0" step="50000">
                                            <input type="range" class="range-max" min="0" max="10000000" value="10000000" step="50000">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>
                            <div class="size-range">
                                <h6 class="text-uppercase mb-3">Filtrlar</h6>
                                <ul class="list-unstyled mb-0 categories-list">


                                    <li>
                                        <div class="form-group filter">
                                            <input id="num_adults" type="number" class="form-control" placeholder="Kattalar soni">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group filter">
                                            <input id="num_rooms" type="number" class="form-control" placeholder="Xona soni">
                                        </div>
                                    </li>

                                    <li>
                                        <div>
                                            <select id="room_type" class="form-select">
                                                <option value="">Xona turi</option>
                                                <option value="single">Single</option>
                                                <option value="double">Double</option>
                                                <option value="suite">Suite</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <select id="bed_type" name="bed" class="form-select">
                                            <option value="">Yotoq turini tanlang</option>
                                            <option value="queen">Queen</option>
                                            <option value="king">King</option>
                                            <option value="twin">Twin</option>
                                        </select>
                                    </li>
                                    
                                    <br>
                                    <label>Hotel Filter</label>
                                    <li>
                                        <div>
                                            <select id="city" name="city" class="form-select">
                                                <option value="">Hududni tanlang</option>
                                                <option value="andijan">Andijon</option>
                                                <option value="bukhara">Buxoro</option>
                                                <option value="fergana">Farg'ona</option>
                                                <option value="jizzakh">Jizzax</option>
                                                <option value="namangan">Namangan</option>
                                                <option value="navoi">Navoiy</option>
                                                <option value="samarkand">Samarqand</option>
                                                <option value="sirdarya">Sirdaryo</option>
                                                <option value="surxondarya">Surxondaryo</option>
                                                <option value="tashkent">Toshkent</option>
                                                <option value="karakalpakstan">Qoraqalpog'iston</option>
                                                <option value="khorezm">Xorazm</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <select id="stars" name="stars" class="form-select">
                                                <option value="">Yulduzlar</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="wifi">
                                            <label class="form-check-label" for="wifi">Wifi</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="air_conditioner">
                                            <label class="form-check-label" for="air_conditioner">Konditsioner</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="breakfast">
                                            <label class="form-check-label" for="breakfast">Nonushta</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="parking">
                                            <label class="form-check-label" for="parking">Avtoturargoh</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="swimming_pool">
                                            <label class="form-check-label" for="swimming_pool">Suzish havzasi</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="gym">
                                            <label class="form-check-label" for="gym">Sport zali</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="restaurant_bar">
                                            <label class="form-check-label" for="restaurant_bar">Restoran bar</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-12" id="product-display">
                <div class="product-wrapper">
                    <div class="product-grid">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3" id="products-container">
                            <!-- Mahsulotlar bu yerda ko'rsatiladi -->
                        </div>
                    </div>
                    <hr>
                    <nav class="d-flex justify-content-between" aria-label="Sahifa navigatsiyasi" id="pagination-container">
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const token_ = localStorage.getItem("access_token");
   
    // Ma'lumotlarni olish va mahsulotlarni chizish funksiyasi
    document.getElementById("search-button").addEventListener("click", async function() {
        var firstFilter = {
            city: '',
            bed_type: '',
            num_adults: 0,
            num_rooms: 0,
            start_date: 0,
            end_date: 0,
            // per_page: 12,
        }
        
        const params = new URLSearchParams();

        firstFilter.city = document.getElementById("filter_city").value;
        firstFilter.bed_type = document.getElementById("filter_bed_type").value;
        firstFilter.num_adults = document.getElementById("filter_num_adults").value;
        firstFilter.num_rooms = document.getElementById("filter_num_rooms").value;
        firstFilter.start_date = document.getElementById("filter_start_date").value;
        firstFilter.end_date = document.getElementById("filter_end_date").value;

        // Parametrlarga faqat ularning qiymatlari berilgan bo'lsa qo'shamiz
        for (const [key, value] of Object.entries(firstFilter)) {
            if (value) params.append(key, value);
        }
        console.log(params)
        firstFilterData(params, false)
    });

    function cannotRender(){
        const paginationContainer = document.getElementById("pagination-container");
        const productsContainer = document.getElementById("products-container");
        productsContainer.innerHTML = `
            <p style="width: 100%; text-align: center; font-size:20px">Sizning so'rovingiz bo'yicha mehmonxonalar topilmadi!</p>
        `
        paginationContainer.innerHTML = null;
    }

    async function firstFilterData(params='', isFirst=false){

        const url = `${local_url}/api/hotel/items/filter/v2?${params.toString()}`;
        // console.log(url)
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token_}`, // YOUR_ACCESS_TOKEN o'rniga haqiqiy token bilan almashtiring
                },
            });
            
            // Filtrlar va mahsulotlarni ko'rsatish
            if(isFirst === false){
                document.getElementById("search-block").classList.add("d-none");
                document.getElementById("filter-sidebar").classList.remove("d-none");
                document.getElementById("product-display").classList.remove("col-xl-12");
                document.getElementById("product-display").classList.add("col-xl-9");
            }

            if (!response.ok) {
                cannotRender();
                console.error("Ma'lumotlarni olish muvaffaqiyatsiz bo'ldi:", response.status);
                return;
            }

            const data = await response.json();
            hotels = data.hotels;
            console.log(data)

            renderData(data)
        } catch (error) {
            console.error("Ma'lumotlarni olishda xato:", error);
        }
    }
    // firstFilterData('', true);

    const filterState = {
        price_min: '',
        price_max: '',
        bed_type: '',
        gym: 0,
        wifi: 0,
        air_conditioner: 0,
        breakfast: 0,
        parking: 0,
        swimming_pool: 0,
        restaurant_bar: 0,
        stars: '',
        city: '',
        room_type: '',
        num_adults: 0,
        num_rooms: 0,
        // per_page: 12,
        page: 1,
    };
    var minVal, maxVal;

    // MIN-MAX narx slayderi
    const rangeInput = document.querySelectorAll(".range-input input"),
    priceInput = document.querySelectorAll(".price-input input"),
    range = document.querySelector(".slider .progress");
    let priceGap = 1000;

    rangeInput.forEach(input =>{
        input.addEventListener("input", e =>{
            minVal = parseInt(rangeInput[0].value),
            maxVal = parseInt(rangeInput[1].value);

            if((maxVal - minVal) < priceGap){
                if(e.target.className === "range-min"){
                    rangeInput[0].value = maxVal - priceGap
                }else{
                    rangeInput[1].value = minVal + priceGap;
                }
            }else{
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
            }
        });
    });

    rangeInput.forEach(input => {
        input.addEventListener('mouseup', e =>{
            filterData();
        })
    })

    // Функция для получения данных и отрисовки продуктов

    async function filterData(filterOn=true) {
        const params = new URLSearchParams();

        // Получаем данные из боковой панели фильтров и обновляем состояние фильтров
        filterState.price_min = minVal;
        filterState.price_max = maxVal;
        filterState.bed_type = document.getElementById('bed_type').value;
        filterState.gym = document.getElementById("gym").checked ? 1 : 0;
        filterState.wifi = document.getElementById("wifi").checked ? 1 : 0;
        filterState.air_conditioner = document.getElementById("air_conditioner").checked ? 1 : 0;
        filterState.breakfast = document.getElementById("breakfast").checked ? 1 : 0;
        filterState.parking = document.getElementById("parking").checked ? 1 : 0;
        filterState.swimming_pool = document.getElementById("swimming_pool").checked ? 1 : 0;
        filterState.restaurant_bar = document.getElementById("restaurant_bar").checked ? 1 : 0;
        filterState.stars = document.getElementById("stars").value;
        filterState.city = document.getElementById("city").value;
        filterState.room_type = document.getElementById("room_type").value;
        filterState.num_adults = document.getElementById("num_adults").value;
        filterState.num_rooms = document.getElementById("num_rooms").value;

        // Добавляем параметры только если они заданы
        for (const [key, value] of Object.entries(filterState)) {
            if (value) params.append(key, value);
        }

        const url = `${local_url}/api/hotel/items/filter/v2?${params.toString()}`;
        // console.log(url)    

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token_}`, // Замените YOUR_ACCESS_TOKEN на фактический токен
                },
            });

            if (!response.ok) {
                cannotRender()
                console.error("Не удалось получить данные:", response.status);
                return;
            }

            const data = await response.json();

            if(filterOn){
                // Показываем фильтры и продукты
                document.getElementById("filter-sidebar").classList.remove("d-none");
                // document.getElementById("product-display").classList.remove("d-none");

                const productsContainer = document.getElementById("products-container");
                productsContainer.innerHTML = ""; // Очищаем предыдущие результаты
            }
            // console.log('adasdasda',data)
            hotels = data.hotels;
            renderData(data);
        } catch (error) {
            console.error("Ошибка при получении данных:", error);
        }
    }

    // Добавляем обработчики событий для всех элементов фильтра
    const filterInputs = [
        'price_min', 'price_max', 'bed_type', 'wifi', 'air_conditioner', 'breakfast', 
        'parking', 'swimming_pool', 'gym', 'restaurant_bar', 'stars', 
        'city', 'room_type', 'capacity', 'num_adults', 'num_rooms',
    ];
    // Обновляем обработчики событий для фильтров
    filterInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', filterData); // для input
            if (element.type === 'checkbox') {
                element.addEventListener('change', filterData); // для чекбоксов
            }
            if (element.type === 'radio') {
                element.addEventListener('change', filterData); // для чекбоксов
            }
            if (element.tagName === 'SELECT') {
                element.addEventListener('change', filterData); // для селектов
            }
            if (element.type === 'number') {
                element.addEventListener('input', filterData); // для number input
            }
        }
    });

    function renderData(data) {
        const productsContainer = document.getElementById("products-container");
        productsContainer.innerHTML = ""; // Очищаем предыдущие результаты

        rooms = [];
        // Отрисовка отелей
        data.hotels.forEach(product => {
            const productHTML = `
            <div class="col">
                <div class="card rounded-0 product-card">
                    <img src="${local_url}/api/hotel/${product.images[0]}" class="card-img-top" alt="${product.name}">
                    <div class="card-body">
                        <h6 class="product-name mb-2">${product.name}</h6>
                        <div class="product-action mt-2">
                            <div class="d-grid gap-2">
                                <a href="javascript:;" class="btn btn-light btn-ecomm"   onclick="toCart(\`${product.hotel_id}\`, 'hotel')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct">Add to cart</a>
                                <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
                                    onclick="showHotelDetails('${product.name}', '', ${product.stars}, '', '${product.location}', '${product.wifi}', '${product.breakfast}', '${product.gym}', '${product.swimming_pool}', '${product.parking}', '${product.restaurant_bar}', '${product.images}')">
                                    <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            productsContainer.insertAdjacentHTML("beforeend", productHTML);
        });

        // Отрисовка пагинации
        renderPagination(data.pagination.current_page, data.pagination.pages);
    }





    
    function renderPagination(currentPage, totalPages) {
        const paginationContainer = document.getElementById("pagination-container");
        const paginationHTML = `
            <ul class="pagination">
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:;" onclick="goToPage(${currentPage - 1})"><i class='bx bx-chevron-left'></i> Back</a>
                </li>
            </ul>
            <ul class="pagination">
                ${Array.from({ length: totalPages }, (_, index) => `
                    <li class="page-item ${currentPage === index + 1 ? 'active' : ''}">
                        <a class="page-link" href="javascript:;" onclick="goToPage(${index + 1})">${index + 1}</a>
                    </li>
                `).join('')}
            </ul>
            <ul class="pagination">
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:;" onclick="goToPage(${currentPage + 1})">Next <i class='bx bx-chevron-right'></i></a>
                </li>
            </ul>
        `;
        paginationContainer.innerHTML = paginationHTML;
    }


    function goToPage(pageNumber) {
        window.scrollTo(0, 0)
        filterState.page = pageNumber
        filterData(false);
        // // Имитация запроса к серверу
        // fetch(`${local_url}/api/hotel/items/filter?page=${pageNumber}`)
        //     .then(response => response.json())
        //     .then(data => renderData(data))
        //     .catch(error => console.error('Ошибка при загрузке данных:', error));
    }

</script>

<?php include 'components/footer.php'; ?>
