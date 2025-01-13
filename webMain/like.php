<?php include 'components/header.php'; ?>


<style>
    #paginationContainer
    {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #paginationContainer button
    {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border-radius: 50%;
        border: none;
    }
</style>

<section class="py-4">
    <div class="container">
        <div id="cardsContainer" class="row"></div>
        <div id="paginationContainer"></div>
    </div>
</section>









<script>



// Функция для рендеринга карточек
function renderCards(like_id, data, type) {
    switch (type) {
        case 'car':
            data.car.company = {id: data.company.id, name: data.company.name}
            cars.push(data.car);
            document.getElementById('cardsContainer').innerHTML += `
                <div class="col-md-4 col-xl-3 col-sm-6">
                    <div class="card rounded-0 product-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <a href="javascript:;">
                                </a>
                                <a href="javascript:;">
                                    <div class="product-wishlist" onclick="toDisLike(${like_id})"><i class="fa fa-trash"></i></div>
                                </a>
                            </div>
                        </div>
                        <a href="javascript:;">
                            <img src="data:image/png;base64,${data.car.image}"
                                    class="card-img-top" 
                                    alt="Mashinaga oid tasvir" 
                                    style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <div class="product-info">
                                <a href="javascript:;">
                                    <p class="product-category font-13 mb-1">${data.company.name}</p>
                                </a>
                                <a href="javascript:;">
                                    <h6 class="product-name mb-2">${data.car.model}</h6>
                                </a>
                                <div class="d-flex align-items-center">
                                    <div class="mb-1 product-price">
                                        <span class="text-white fs-5">$${data.car.price}</span>
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
                                        <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${data.car.id}\`, 'car')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"><i class='bx bxs-cart-add'></i> Buyurtma Berish</a>
                                        <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct" onclick="showCarDetails(\`${data.car.model}\`, \`${data.car.price}\`, \`${data.company.name}\`, \`${data.car.image}\`, \`${data.car.color}\`, \`${data.car.year}\`, \`${data.car.seats}\`, \`${data.car.fuel_type}\`, \`${data.car.transmission}\`, \`${data.car.deposit}\`, \`${data.car.insurance}\`, \`${data.car.comment}\`)">
                                            <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'hotel':
            hotels.push(data.hotels)
            document.getElementById('cardsContainer').innerHTML += 
                `<div class="col-md-4 col-xl-3 col-sm-6">
                    <div class="card rounded-0 product-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <a href="javascript:;"></a>
                                <a href="javascript:;">
                                    <div class="product-wishlist" onclick="toDisLike(${like_id})"><i class="fa fa-trash"></i></div>
                                </a>
                            </div>
                        </div>
                        <a href="javascript:;">
                            <img src="${local_url}/api/hotel${data.hotels[0].images[0]}" 
                                class="card-img-top" 
                                alt="Mehmonxona rasmi" 
                                style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <div class="product-info">
                                <a href="javascript:;">
                                    <h6 class="product-name mb-2">${data.hotels[0].name}</h6>
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
                                        onclick="showHotelDetails('${data.hotels[0].name}', '${data.hotels[0].address}', ${data.hotels[0].stars}, '${data.hotels[0].comments}', '${data.hotels[0].location}', '${data.hotels[0].wifi}', '${data.hotels[0].breakfast}', '${data.hotels[0].gym}', '${data.hotels[0].swimming_pool}', '${data.hotels[0].parking}', '${data.hotels[0].restaurant_bar}', '${data.hotels[0].images}')">
                                        <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            break;
        case 'tour':
            tours.push(data.tour)
            document.getElementById('cardsContainer').innerHTML += `
                <div class="col-md-4 col-xl-3 col-sm-6">
                        <div class="card rounded-0 product-card">
                            <div class="card-header bg-transparent border-bottom-0">
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <div class="product-wishlist" onclick="toDisLike(${like_id})"><i class="fa fa-trash"></i></div>
                                </div>
                            </div>
                            <a href="javascript:;">
                                <img src="${local_url}/api/tour${data.tour.images[0]}" 
                                    class="card-img-top" 
                                    alt="Tur rasmi" 
                                    style="width: 100%; height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body">
                                <h6 class="product-name mb-2">${data.tour.title}</h6>
                                <div class="product-price">
                                    <div class="cursor-pointer ms-auto">
                                        <i class="bx bxs-calendar" onclick="showDepartures(${data.tour.id})"></i>
                                    </div>
                                </div>
                                <div class="product-action mt-2">
                                    <div class="d-grid gap-2">
                                        <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${data.tour.id}\`, 'tour')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct">
                                            <i class='bx bxs-cart-add'></i> Buyurtma qilish
                                        </a>
                                        <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
                                        onclick="showTourDetails(${data.tour.id})">
                                        <i class='bx bx-zoom-in'></i> Batafsil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            break;
        default:
            console.error('Unknown product type');
            return;
    }
}

var current_page = 1;
// Функция для отображения пагинации
function renderPagination(pagination) {
    const paginationContainer = document.getElementById('paginationContainer');
    paginationContainer.innerHTML = ''; // Очищаем контейнер пагинации

    // Создание кнопок пагинации
    for (let i = 1; i <= pagination.total_pages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.onclick = function () {
            
            document.getElementById('cardsContainer').innerHTML = null;
            current_page = i;
            fetchLikedData(i, 10); // Запросить данные для этой страницы
        };

        paginationContainer.appendChild(pageButton);
    }
}

function renderLikedItems(items){
    if(!items.length){
        document.getElementById('cardsContainer').innerHTML += `<h1 align="center">У вас еще нет избранных товаров!</h1>`
        return;
    }
    
    document.getElementById('cardsContainer').innerHTML = null;
    items.forEach(e => {
        let type = e.product_type;
        let apiUrl = '';
        switch (type) {
            case 'car':
                apiUrl = `/api/rentcar/companies/web_main/car/${e.product_id}`;
                break;
            case 'hotel':
                apiUrl = `/api/hotel/items/company/${e.product_company_id}`;
                break;
            case 'tour':
                apiUrl = `/api/tour/item/${e.product_id}`;
                break;
            default:
                console.error('Unknown product type');
                return;
        }
        // Опции для fetch с заголовками авторизации
        const headers = new Headers({
            'Authorization': `Bearer ${localStorage.access_token}`,
            'Content-Type': 'application/json' // или другие заголовки, если необходимо
        });

        fetch(local_url+apiUrl, {
					method: 'GET',
					headers: headers
				})
            .then(response => response.json())
            .then(data => {
                // Рендерим карточки
                console.log(data)
                renderCards(e.id, data, type);
                // Отображаем пагинацию
            })
            .catch(error => console.error('Error fetching data:', error));

    })
    
}


// Инициализация страницы
document.addEventListener('DOMContentLoaded', () => {
    fetchLikedData(1, 10); // Загружаем данные для первой страницы
    fetchUserOrdersWithDelay();
});

function toDisLike(like_id){
    // Опции для fetch с заголовками авторизации
    const headers = new Headers({
        'Authorization': `Bearer ${localStorage.access_token}`,
        'Content-Type': 'application/json' // или другие заголовки, если необходимо
    });
    fetch(`${local_url}/api/like/item/${like_id}`, {
            method: 'DELETE',
            headers: headers
        })
        .then(response => response.json())
        .then(data => {
            // Рендерим карточки
            // console.log(data)
            window.location.reload()
        })
        .catch(error => console.error('Error fetching data:', error));
}


</script>




<?php include 'components/footer.php'; ?>