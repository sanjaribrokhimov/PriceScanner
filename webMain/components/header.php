<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>priceScanner</title>
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<link href="assets/plugins/OwlCarousel/css/owl.carousel.min.css" rel="stylesheet" />
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<script src="js/toOrder.js"></script>
	<script src="js/productDetails.js"></script>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<script>
		var local_url = '';
		
		var cars = [];
		var tours = [];
		var hotels = [];
		var rooms = [];
		var foundCmp;
		var liked = [];
		const tf = {
			'false': 'Yo\'q', 
			'true': 'Mavjud'
		};
		
		var unavailableDates = [];

		let currentMonth = new Date().getMonth();  // Текущий месяц
		let currentYear = new Date().getFullYear(); // Текущий год
		let selectedStartDate = null;
		let selectedEndDate = null;

		const months = [
			"Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", 
			"Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
		];

		function toHotels(){
			window.location.href = "hotel.php"
		}

		// Функция для получения данных из API с токеном авторизации
		async function fetchLikedData(page=1, per_page=100) {
			try {

				// Опции для fetch с заголовками авторизации
				const headers = new Headers({
					'Authorization': `Bearer ${localStorage.access_token}`,
					'Content-Type': 'application/json' // или другие заголовки, если необходимо
				});

				// Делаем запрос к API
				const response = await fetch(`${local_url}/api/like/?per_page=${per_page}&page=${page}`, {
					method: 'GET',
					headers: headers
				});

				const data = await response.json();

				// Проверяем успешность получения данных
				if (response.ok) {
					liked = data.items;
					renderLikedItems(data.items)
					renderPagination(data.pagination);
				} else {
					console.error("Ошибка получения данных:", data);
				}
			} catch (error) {
				console.error("Ошибка при запросе:", error);
			}
		}
		fetchLikedData(page=1, per_page=100)
		
		// Получаем текущий URL
		var urlParams = new URLSearchParams(window.location.search);

		// Извлекаем значение параметра 'query'
		var query = urlParams.get('query');
		var category = urlParams.get('category');
		// alert(query)

		var carModalBody = `
		<div class="col-12">
            <div class="image-zoom-section">
                <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                    <div class="item">
                        <img id="modalCarImage" class="img-fluid" alt="Tasvir">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 infoModal">
            <div class="product-info-section p-3">
                <h3 class="mt-3 mt-lg-0 mb-0" id="modalCarModel">error</h3>
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
		
		<div class="cont-cal col-12 col-lg-6" style="width: 350px">
			<div class="calendar-container" style="font-size: 25px">
				<div class="month-header" style="width: 350px">
					<button onclick="prevMonth()">&#8592;</button>
					<div class="month-name month-name-for-date">Месяц</div>
					<button onclick="nextMonth()">&#8594;</button>
				</div>
				<div class="calendar calendar-for-date"></div>
			</div>
		</div>
		`

		var tourModalBody = `
			<div class="col-12 col-lg-6">
				<div class="image-zoom-section">
					<div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
						<div class="item">
							<img id="modalCarImage" class="img-fluid" alt="Tasvir">
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6 p-3 infoModal">
				<h3 class="mt-3 mt-lg-0 mb-0" id="modalTourTitle">Error</h3>
				<div id="modalTourDepartures" class="mt-3"></div>
				<p id="modalTourDescription" class="mt-3">Error</p>
				<div id="modalTourVideo" class="mt-3">Video</div>
				<div id="modalTourPrice" class="text-white fs-5 mt-3">$0.00</div>
			</div>
		`
		var hotelModalBody = `
			<div class="col-12 col-lg-6">
				<div class="image-zoom-section">
					<div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
						<div class="item">
							<img id="modalCarImage" class="img-fluid" alt="Tasvir">
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6 infoModal">
				<div class="product-info-section p-3 hotelModal">
					<h3 class="mt-3 mt-lg-0 mb-0" id="modalHotelModel">error</h3>
					<dl class="row mt-3">
						<dt class="col-sm-3">Address</dt>
						<dd class="col-sm-9" id="modalHotelAddress">error</dd>
						<dt class="col-sm-3">Yulduzlar</dt>
						<dd class="col-sm-9" id="modalHotelStars">error</dd>
						<dt class="col-sm-3">Joylashuv</dt>
						<dd class="col-sm-9" id="modalHotelLocation">error</dd>
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
					</dl>
				</div>
			</div>
		`
	</script>
	
	<script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({
				pageLanguage: 'uz',
				includedLanguages: 'en,ru,uz', 
				layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
				autoDisplay: false
			}, 'google_translate_element');
		}
	</script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	



</head>


<!-- Модальное окно для отображения подробной информации о машине -->
<div class="modal fade carModal" id="QuickViewProduct" style="z-index: 10000">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down">
        <div class="modal-content text-dark rounded-0 border-0">
            <div class="modal-body">
                <button type="button" style="color: #000" class="btn-close float-end" data-bs-dismiss="modal"></button>
                <div class="row g-0" id="modalToRender">
                </div>
            </div>
        </div>
    </div>
</div>



<body style="font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif" class="bg-theme bg-theme1">
	<b class="screen-overlay"></b>
	
		<div class="header-wrapper bg-dark-2">
			<div class="top-menu border-bottom">
				<div class="container">
					<nav class="navbar navbar-expand to-social-links">
						<ul class="navbar-nav">
							<div class="col col-md-auto ms-auto text-end">
								<button class="nav-link cart-link" onclick="history.back()" style="background: transparent; border: none; padding: 5px; margin-left:-22px">
									<i class='bx bx-arrow-back' style="width: 36px; height: 36px; ; ;  display: flex; align-items: center; justify-content: center; font-size: 18px; transition: all 0.3s ease;"></i>
								</button>
							</div>
							<div class="col col-md-auto" onclick='window.location.href="index.php"' style="cursor: pointer">
								<div class="d-flex align-items-center">
									<h3 style="font-weight: 600; margin: 0; font-size: 2.5em">PriceScanner</h3>
								</div>
							</div>
						</ul>
					</nav>
				</div>
			</div>
			<div class="header-content pb-3 pb-md-0">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-12 col-md order-4 order-md-2 justify-content-center d-flex">
							<div class="input-group flex-nowrap" style="max-width: 1000px">
								<input type="text" class="form-control w-100 bg-white" id="query" style="color: #000" placeholder="Qidirish">
								 
								<select id="category" class="form-select">
									<option value="hotel">Hotel</option>
									<option value="car">RentCar</option>
									<option value="tour">Tour</option>
								</select>
								<span class="input-group-text cursor-pointer" id="search-data">
									<i class='bx bx-search'></i>
								</span>
							</div>
						</div>
						<div class="col col-md-auto order-2 order-md-4">
							<div class="top-cart-icons">
								<nav class="navbar navbar-expand">
									<ul class="navbar-nav ms-auto">
										<li class="nav-item">
											<a href="./signin.php" class="nav-link cart-link" id="userLink">
												<i class='bx bx-user'></i>
											</a>
										</li>

										<script>
											document.addEventListener("DOMContentLoaded", function() {
												const accessToken = localStorage.getItem("access_token");
												const email = localStorage.getItem("email");

												const userLink = document.getElementById("userLink");
												userLink.addEventListener("click", function(event) {
													if (accessToken && email) {
														event.preventDefault(); // Предотвращаем переход по ссылке
														window.location.href = "./dashboard_user.php"; // Перенаправление на dashboard_user.php
													}
												});
											});
										</script>

										<li class="nav-item">
											<a href="like.php" class="nav-link cart-link">
												<i class='bx bx-heart' style="color:crimson;"></i>
											</a>
										</li>
										<li class="nav-item dropdown dropdown-large">
											<a href="#" class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative cart-link" data-bs-toggle="dropdown">
												<i class='bx bx-shopping-bag not-after'></i>
											</a>
											<div class="dropdown-menu dropdown-menu-end order-list" id="forMobile">
												<a href="javascript:;">
													<div class="cart-header">
														<p class="cart-header-title mb-0">Xaridlar</p>
													</div>
												</a>
												<div class="cart-list">
													<!-- Товары в корзине -->
												</div>
												<a href="javascript:;">
													<div class="text-center cart-footer d-flex align-items-center">
														<h5 class="mb-0">Umumiy</h5>
														<h5 class="mb-0 ms-auto">$999999</h5>
													</div>
												</a>
											</div>
										</li>			
										<li class="nav-item dropdown" style="margin-left: 10px">
											<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" style="margin-right: 0 !important;padding-right: 0 !important;">
												<i class="fas fa-dollar-sign"></i> USD
											</a>
											<ul class="dropdown-menu dropdown-menu-lg-end">
												<li><a class="dropdown-item" href="#"><i class="fas fa-dollar-sign"></i> USD</a></li>
												<li><a class="dropdown-item" href="#"><i class="fas fa-som"></i> SUM</a></li>
											</ul>
										</li>
										<li class="nav-item dropdown">
											<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
												<div class="lang d-flex gap-1">
													<i class="fas fa-globe"></i>
												</div>
												<span id="current-lang">UZB</span>
											</a>
											<div class="dropdown-menu dropdown-menu-lg-end">
												<a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('uz')">
													<i class="flag-icon flag-icon-uz me-2"></i><span>O'zbek</span>
												</a>
												<a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('ru')">
													<i class="flag-icon flag-icon-ru me-2"></i><span>Русский</span>
												</a>
												<a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('en')">
													<i class="flag-icon flag-icon-us me-2"></i><span>English</span>
												</a>
											</div>
										</li>
									</ul>
									
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="menu3" >
				<div >
					
				</div>
				<div class="container hide">
					<nav id="navbar_main" class="mobile-offcanvas navbar navbar-expand-lg" style="margin-left: 0; padding: 0;">
						
						<ul class="navbar-nav flex-row"> <!-- Горизонтальное меню -->
							<li class="nav-item">
								<a class="nav-link icon-circle" href="./index.php">
									<i class="fas fa-home"></i>
									<span class="menu-icon-span" data-translate="home">Bosh Saxifa</span> <!-- Иконка домика -->
								</a>
							</li>
							<li class="nav-item">
								<hr class="separator" /> <!-- Разделитель -->
							</li>
							<li class="nav-item">
								<a class="nav-link icon-circle" href="tour.php">
									<i class="fas fa-plane"></i>
									<span class="menu-icon-span" data-translate="tour">Tour</span> <!-- Иконка самолета -->
								</a>
							</li>
							<li class="nav-item">
								<hr class="separator" /> <!-- Разделитель -->
							</li>
							<li class="nav-item">
								<a class="nav-link icon-circle" href="rentcar.php">
									<i class="fas fa-car"></i>
									<span class="menu-icon-span" data-translate="car">RentCar</span> <!-- Иконка машины -->
								</a>
							</li>
							<li class="nav-item">
								<hr class="separator" /> <!-- Разделитель -->
							</li>
							<li class="nav-item">
								<a class="nav-link icon-circle" href="hotel.php">
									<i class="fas fa-hotel"></i>
									<span class="menu-icon-span" data-translate="hotel">Hotel</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>

		