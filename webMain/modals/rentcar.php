<!-- Mashinalarni ijaraga olish -->
 <script src="js/productDetails.js"></script>
<script>

function fetchCars(page = 1) {
    fetch(`${local_url}/api/rentcar/companies/web_main/all_cars?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                const randomCars = getRandomCars(data.cars, 8);
                renderCars(randomCars);
                cars = data.cars;
            }
        })
        .catch(error => console.error('Mashinalarni olishda xatolik yuz berdi:', error));
}

function getRandomCars(cars, count) {
    const shuffled = cars.sort(() => 0.5 - Math.random()); // Massivni aralashtirish
    return shuffled.slice(0, count); // Dastlabki 'count' elementlarni olish
}

function renderCars(cars) {
    const carList = document.getElementById('car-list');
    carList.innerHTML = ''; // Oldingi kontentni tozalash

    let row;
    cars.sort((a, b) => a.price - b.price);

    cars.slice(0, 4).forEach((car, index) => {
        let isLiked = `bx-heart`;
        // Har 4ta mashinadan keyin yangi qatordan boshlash
        if (index % 4 === 0) {
            row = document.createElement('div');
            row.className = 'row';
            carList.appendChild(row);
        }

        if(liked.length){
            liked.forEach(like => {
                if(like.product_type === "car" && like.product_company_id === car.company.id && like.product_id === car.id){
                    isLiked = `bxs-heart`;
                }
            })
        }

        // Tasodifiy narxni kamaytirish (0 dan past bo'lmasligi kerak)
        const discountedPrice = Math.max(car.price - Math.floor(Math.random() * 20), 0);
        // console.log(car)
        const carHTML = `
                <div class="col-md-4 col-xl-3 col-sm-6">
                    <div class="card rounded-0 product-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <a href="javascript:;">
                                </a>
                                <a href="javascript:;">
                                    <div class="product-wishlist" onclick="toLike(${car.id}, ${car.company.id}, 'car', this)"><i class='bx ${isLiked}'></i></div>
                                </a>
                            </div>
                        </div>
                        <a href="javascript:;">
                            <img src="data:image/png;base64,${car.image}" 
                                 class="card-img-top" 
                                 alt="Mashinaga oid tasvir" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <div class="product-info">
                                <a href="javascript:;">
                                    <p class="product-category font-13 mb-1">${car.company.name}</p>
                                </a>
                                <a href="javascript:;">
                                    <h6 class="product-name mb-2">${car.model}</h6>
                                </a>
                                <div class="d-flex align-items-center">
                                    <div class="mb-1 product-price">
                                        <span class="me-1 text-decoration-line-through">$${discountedPrice}.00</span>
                                        <span class="text-white fs-5">$${car.price}</span>
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
                                        <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${car.id}\`, 'car')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"><i class='bx bxs-cart-add'></i> Buyurtma Berish</a>
                                        <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct" onclick="showCarDetails(\`${car.model}\`, \`${car.price}\`, \`${car.company.name}\`, \`${car.image}\`, \`${car.color}\`, \`${car.year}\`, \`${car.seats}\`, \`${car.fuel_type}\`, \`${car.transmission}\`, \`${car.deposit}\`, \`${car.insurance}\`, \`${car.comment}\`, \`${car.id}\`)">
                                            <i class='bx bx-zoom-in'></i>Batafsil Ma'lumot
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

        row.innerHTML += carHTML;
    });
}




</script>

<!-- Mashinalar ro'yxati uchun konteyner -->
<div id="car-list"></div>
