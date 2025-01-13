<?php include 'components/header.php'; ?>

<style>
.card-img-top {
    height: 200px;
}

#products-container div.col {
    padding-bottom: 10px;
    padding-top: 10px;
}

.page-item {
    list-style: none;
}
</style>

<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Фильтры и продукты скрыты по умолчанию -->
            <div class="col-12 col-xl-3" id="filter-sidebar">
                <div class="btn-mobile-filter d-xl-none"><i class='bx bx-slider-alt'></i></div>
                <div class="filter-sidebar d-xl-flex" style="z-index: 10000">
                    <div class="card rounded-0 w-100">
                        <div class="card-body">
                            <div class="align-items-center d-flex d-xl-none">
                                <h6 class="text-uppercase mb-0">Filtr</h6>
                                <div class="btn-mobile-filter-close btn-close ms-auto cursor-pointer"></div>
                            </div>
                            <hr class="d-flex d-xl-none" />
                            <div class="price-range">
                                <h6 class="text-uppercase mb-3">Narxi</h6>
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
                                            <input type="range" class="range-min" min="0" max="10000000" value="0"
                                                step="50000">
                                            <input type="range" class="range-max" min="0" max="10000000"
                                                value="10000000" step="50000">
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
                                            <input id="title" type="text" class="form-control" placeholder="Title">
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-center">
                                        <div class="row" style="width: 100%;">
                                            <div class="form-group filter col-6" style="margin-right: 0; padding-right: 0;">
                                                <input id="fromCountry" type="text" class="form-control" placeholder="Dan" style="border-radius: 5px 0 0 5px;">
                                            </div>
                                            <div class="form-group filter col-6" style="margin-left: 0; padding-left: 0;">
                                                <input id="toCountry" type="text" class="form-control" placeholder="Ga" style="border-radius: 0 5px 5px 0;">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <select id="tour_category" name="category" class="form-select">
                                                <option value="">Kategoriya</option>
                                                <option value="all">Barchasi</option>
                                                <option value="family">Oila</option>
                                                <option value="beach">Sohil</option>
                                                <option value="sunny">Quyoshli</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <select id="status" name="status" class="form-select">
                                                <option value="">Holat</option>
                                                <option value="active">Faol</option>
                                                <option value="unactive">Faol emas</option>
                                            </select>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-9" id="product-display">
                <div class="product-wrapper">
                    <div class="product-grid">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3" id="products-container">
                            <!-- Продукты будут отрисованы здесь -->
                        </div>
                    </div>
                    <hr>
                    <nav class="d-flex justify-content-between" aria-label="Page navigation" id="pagination-container">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="javascript:;"><i
                                        class='bx bx-chevron-left'></i> Orqaga</a></li>
                        </ul>
                        <ul class="pagination">
                            <li class="page-item active d-none d-sm-block" aria-current="page"><span
                                    class="page-link">1<span class="visually-hidden">(joriy)</span></span></li>
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="javascript:;">2</a></li>
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="javascript:;">3</a></li>
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="javascript:;">4</a></li>
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="javascript:;">5</a></li>
                        </ul>
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="javascript:;" aria-label="Следующий">Keyingisi
                                    <i class='bx bx-chevron-right'></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const token_ = localStorage.getItem("access_token");
const filterState = {
    min_price: '',
    max_price: '',
    title: '',
    fromCountry: '',
    toCountry: '',
    category: '',
    status: '',
    // per_page: 2,
    page: 1,
};
var minVal, maxVal;



function cannotRender() {
    const paginationContainer = document.getElementById("pagination-container");
    const productsContainer = document.getElementById("products-container");
    productsContainer.innerHTML = `
            <p style="width: 100%;text-align: center;font-size:20px">Sizning soʻrovlaringiz asosida sayohat topilmadi.!</p>
        `
    paginationContainer.innerHTML = null;
}

// Добавляем обработчики событий для всех элементов фильтра
const filterInputs = [
    'min_price', 'max_price', 'title', 'fromCountry', 'toCountry', 'tour_category', 'status',
];

async function firstFilterData(params = '', isFirst = false) {

    const url = `${local_url}/api/tour/items/tours/filtered?page=${filterState.page}`;
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token_}`, // Замените YOUR_ACCESS_TOKEN на фактический токен
            },
        });

        if (!response.ok) {
            console.error("Не удалось получить данные:", response.status);
            return;
        }

        const data = await response.json();

        // Показываем фильтры и продукты
        if (isFirst === false) {
            document.getElementById("product-display").classList.remove("col-xl-12");
            document.getElementById("product-display").classList.add("col-xl-9");
        }

        renderData(data)
    } catch (error) {
        console.error("Ошибка при получении данных:", error);
    }
}
firstFilterData('', true);


// MIN-MAX price slider
const rangeInput = document.querySelectorAll(".range-input input"),
    priceInput = document.querySelectorAll(".price-input input"),
    range = document.querySelector(".slider .progress");
let priceGap = 1000;

rangeInput.forEach(input => {
    input.addEventListener("input", e => {
        minVal = parseInt(rangeInput[0].value),
            maxVal = parseInt(rangeInput[1].value);

        if ((maxVal - minVal) < priceGap) {
            if (e.target.className === "range-min") {
                rangeInput[0].value = maxVal - priceGap
            } else {
                rangeInput[1].value = minVal + priceGap;
            }
        } else {
            priceInput[0].value = minVal;
            priceInput[1].value = maxVal;
            range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
            range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
        }
    });
});

rangeInput.forEach(input => {
    input.addEventListener('mouseup', e => {
        filterData();
    })
})

async function filterData(pageNumber = 1) {
    const params = new URLSearchParams();
    console.log(arguments)

    // Получаем данные из боковой панели фильтров и обновляем состояние фильтров
    filterState.min_price = minVal;
    filterState.max_price = maxVal;
    filterState.title = document.getElementById('title').value;
    filterState.fromCountry = document.getElementById("fromCountry").value;
    filterState.toCountry = document.getElementById("toCountry").value;
    filterState.category = document.getElementById("tour_category").value;
    filterState.status = document.getElementById("status").value;
    // filterState.page = filterState.page; // Обновляем текущую страницу
    console.log(pageNumber)

    // Добавляем параметры только если они заданы
    for (const [key, value] of Object.entries(filterState)) {
        if (value) params.append(key, value);
    }

    const url = `${local_url}/api/tour/items/tours/filtered?${params.toString()}`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token_}`,
            },
        });

        if (!response.ok) {
            console.error("Не удалось получить данные:", response.status);
            if (response.status === 404) cannotRender()
            return;
        }

        const data = await response.json();
        renderData(data);
    } catch (error) {
        console.error("Ошибка при получении данных:", error);
    }
}


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
    }
});

function renderData(data) {
    const productsContainer = document.getElementById("products-container");
    productsContainer.innerHTML = ""; // Очищаем предыдущие результаты



    // Отрисовка продуктов
    data.tours.forEach(product => {


        // Находим минимальную цену среди всех выездов
        const lowestPrice = Math.min(...product.departures.map(d => d.price));
        console.log(lowestPrice)
        // Фильтруем массив выездов, чтобы получить только те выезды, где цена равна минимальной
        const lowerPriceDate = product.departures
            .filter(d => d.price === lowestPrice)
            .map(d => d.departure_date)[0].split("T")[0]; // Извлекаем только даты выездов

        const productHTML = `
                <div class="col">
                    <div class="card rounded-0 product-card">
                        <img src="${local_url}/api/tour${product.images[0]}" class="card-img-top" alt="${product.title}">
                        <div class="card-body">
                            <h6 class="product-name mb-2">${product.title}</h6>
                            <div class="product-price"><span style="color:#fff; font-weight: 600;font-size:20px">$${lowestPrice}</span> dan boshlab</div>
                            <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${product.id}\`, \`${product.company_id}\`, 'tour')" data-bs-toggle="modal" data-bs-target="#gotoSign">Savatchaga qo'shish</a>
                        </div>
                    </div>
                </div>`;
        productsContainer.insertAdjacentHTML("beforeend", productHTML);
    });

    // Отрисовка пагинации
    renderPagination(data.current_page, data.pages);
}

function renderPagination(currentPage, totalPages) {
    const paginationContainer = document.getElementById("pagination-container");
    paginationContainer.innerHTML = ""; // Очищаем существующие элементы пагинации

    // Кнопка "Back"
    const backBtn = document.createElement("li");
    backBtn.classList.add("page-item");
    if (currentPage === 1) backBtn.classList.add("disabled");
    backBtn.innerHTML =
        `<a class="page-link" href="javascript:;" onclick="changePage(${currentPage - 1})"><i class='bx bx-chevron-left'></i> Orqaga</a>`;
    paginationContainer.appendChild(backBtn);

    // Создаем номера страниц
    const perPageBlock = document.createElement('div')
    perPageBlock.classList.add("d-flex");
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = document.createElement("li");
        pageItem.classList.add("page-item");
        if (i === currentPage) pageItem.classList.add("active");
        pageItem.innerHTML = `<a class="page-link" href="javascript:;" onclick="changePage(${i})">${i}</a>`;
        perPageBlock.appendChild(pageItem);
    }
    paginationContainer.appendChild(perPageBlock);

    // Кнопка "Next"
    const nextBtn = document.createElement("li");
    nextBtn.classList.add("page-item");
    if (currentPage === totalPages) nextBtn.classList.add("disabled");
    nextBtn.innerHTML =
        `<a class="page-link" href="javascript:;" onclick="changePage(${currentPage + 1})">Keyingisi <i class='bx bx-chevron-right'></i></a>`;
    paginationContainer.appendChild(nextBtn);
}

// Функция для смены страницы
function changePage(pageNumber) {
    filterState.page = pageNumber
    filterData(); // Загружаем данные для выбранной страницы
}
</script>

<?php include 'components/footer.php'; ?>