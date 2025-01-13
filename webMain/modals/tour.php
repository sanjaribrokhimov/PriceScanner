<script src="js/productDetails.js"></script>
<!-- Turlar -->
<script>
    var tours = '';

    // Serverdan turlarni olish
    function fetchTours(page = 1) {
        fetch(`${local_url}/api/tour/items/all`)
            .then(response => response.json())
            .then(data => {
                if (data.tours) {
                    const randomTours = getRandomTours(data.tours, 8); // 8 ta tasodifiy turni olish
                    renderTours(randomTours); // Turlarni sahifada chiqarish
                    tours = data.tours;
                }
            })
            .catch(error => console.error('Xato yuz berdi:', error));
    }

    // Tasodifiy turlarni tanlash
    function getRandomTours(tours, count) {
        const shuffled = tours.sort(() => 0.5 - Math.random()); // Massivni aralashtirish
        return shuffled.slice(0, count); // Birinchi `count` elementni qaytarish
    }

    // Turlar ro‘yxatini chiqarish
    function renderTours(tours) {
        const tourList = document.getElementById('tour-list');
        tourList.innerHTML = ''; // Joriy ro‘yxatni tozalash

        let row;

        tours.forEach((tour, index) => {
            if (index % 4 === 0) { // Har 4 ta turni yangi qatorga qo‘shish
                row = document.createElement('div');
                row.className = 'row';
                tourList.appendChild(row);
            }
            let isLiked = `bx-heart`;


            // Minimal narxni topish
            const lowestPrice = Math.min(...tour.departures.map(d => d.price));

            // Minimal narxdagi jo‘nash sanasi
            const lowerPriceDate = tour.departures
                .filter(d => d.price === lowestPrice)
                .map(d => d.departure_date)[0].split("T")[0];

                
            liked.forEach(like => {
                if(like.product_type === "tour" && like.product_company_id === tour.company_id && like.product_id === tour.id){
                    isLiked = `bxs-heart`;
                }
            })


            // Bitta tur uchun HTML
            const tourHTML = `
                <div class="col-md-4 col-xl-3 col-sm-6">
                    <div class="card rounded-0 product-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <div class="product-wishlist" onclick="toLike(${tour.id}, ${tour.company_id}, 'tour', this)"><i class='bx ${isLiked}'></i></div>
                            </div>
                        </div>
                        <a href="javascript:;">
                            <img src="${local_url}/api/tour${tour.images[0]}" 
                                class="card-img-top" 
                                alt="Tur rasmi" 
                                style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h6 class="product-name mb-2">${tour.title}</h6>
                            <div class="product-price">
                                <span class="text-white fs-5">Faqat ${lowestPrice}.00 dan</span>
                                <div class="cursor-pointer ms-auto">
                                    <i class="bx bxs-calendar" onclick="showDepartures(${tour.id})"></i>${lowerPriceDate}
                                </div>
                            </div>
                            <div class="product-action mt-2">
                                <div class="d-grid gap-2">
                                    <a href="javascript:;" class="btn btn-light btn-ecomm" onclick="toCart(\`${tour.id}\`, 'tour')" data-bs-toggle="modal" data-bs-target="#QuickViewProduct">
                                        <i class='bx bxs-cart-add'></i> Buyurtma qilish
                                    </a>
                                    <a href="javascript:;" class="btn btn-link btn-ecomm" data-bs-toggle="modal" data-bs-target="#QuickViewProduct"
                                    onclick="showTourDetails(${tour.id})">
                                    <i class='bx bx-zoom-in'></i> Batafsil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            row.innerHTML += tourHTML; // Turni qatordagi ro‘yxatga qo‘shish
        });
    }

    function updatePrice(price) {
        document.getElementById('modalTourPrice').textContent = `$${price}`;
    }

    // Qolgan funksiyalar ham shu kabi tarjima qilinadi.
</script>

<!-- Turlar ro‘yxati uchun konteyner -->
<section class="py-4">
    <div class="container">
        <h5 class="text-uppercase mb-4">Turlar</h5>
        <div id="tour-list" class="row"></div>
    </div>
</section>





