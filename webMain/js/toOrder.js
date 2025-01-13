var orderData = {
    company_id: '',
    user_id: '',
    telephone: '',
    name: '',
    surname: '',
    status: '',
    status: 'IN PROGRESS',
}

var foundProduct = '';

function toLogin() {
    localStorage.clear()
    window.location.href = `signin.php?from=${window.location.pathname.split('/').reverse()[0].split('.')[0]}`
}

var toLoginForm = `
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Предупреждение:</h1>
    </div>
    <div class="modal-body">
        Для заказа нужно зарегистрироваться!
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="toLogin()">Ok</button>
    </div>

`

//LIKE 
async function toLike(product_id, product_company_id, product, good){
    
    var check_h = localStorage.access_token;

    const url = `${local_url}/api/auth/check-health`;
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${check_h}`,
            },
        });

        if (!response.ok) {
            console.error("Не удалось получить данные:", response.status);
            if(confirm("Сперва нужно зарегистрироваться!")) toLogin();
            return;
        }

        const data = await response.json();

        if (data.email !== localStorage.email) checkClientData(product_id, product, false);
        if (localStorage.first_name && localStorage.last_name && localStorage.phone_number && localStorage.email && localStorage.user_id) {
            var requestBody = {
                product_type: product,
                product_id: product_id,
                product_company_id: product_company_id,
                product: "Tashkent",
            }
            const response = await fetch(`${local_url}/api/like/item`, {
                method: 'POST', // Указываем, что это POST-запрос
                headers: {
                    'Content-Type': 'application/json', // Указываем, что передаем JSON
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`, // Пример с токеном авторизации (если нужен)
                },
                body: JSON.stringify(requestBody), // Преобразуем объект в строку JSON
            });
    
            // Парсим ответ в формате JSON
            const data = await response.json();
            // console.log(data)
            if(data.item_id){
                
                // const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                // document.querySelector('#successModal .modal-body').innerText = "Успешно!";
                // successModal.show();
                let like = good.querySelector('i')
                like.classList.remove('bx-heart')
                like.classList.add('bxs-heart')
            }
            if(data.error === "liked before"){
                
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                document.querySelector('#successModal .modal-body').innerText = "Товар уже присутствует в вашем списке избранных!";
                successModal.show();
            }
            return;
            
        } else checkClientData(product_id, product, false);;

    } catch (error) {
        console.error("Ошибка при получении данных:", error);
    }
}



async function toCart(product_id, product) {
    if (product === 'car') {
        orderData.car_id = product_id;
        delete orderData.hotel_id
        delete orderData.tour_id
        foundProduct = cars.find(item => item.id === +product_id);
            
        unavailableDates = foundProduct.availability;
    }

    if (product === 'hotel') {
        delete orderData.car_id;
        delete orderData.tour_id;
        // var foundCmp = hotels.find(item => item.hotel_id === +product_id)
        // console.log(foundCmp)

        // // Преобразуем hotels в массив, если это объект, иначе оставляем как есть (если это уже массив)
        const hotelsArray = Array.isArray(hotels) ? hotels : [hotels];

        // // Теперь можно безопасно использовать .find(), так как hotelsArray - это всегда массив
        // console.log(hotelsArray, foundCmp.id)
        // console.log(product_id)
        foundProduct = hotelsArray.find(item => item.hotel_id === +product_id);
        // console.log(foundProduct)
        
        orderData.hotel_id = foundProduct.hotel_id;
    }

    if (product === 'tour') {
        orderData.tour_id = product_id;
        delete orderData.hotel_id
        delete orderData.car_id
        foundProduct = tours.find(item => item.id === +product_id);
    }

    // console.log(foundProduct)
    orderData.company_id = foundProduct.company?.id || foundProduct.company_id;
    await checkUserData(product_id, product);
}


async function checkUserData(product_id, product){
    var check_h = localStorage.access_token;

    const url = `${local_url}/api/auth/check-health`;
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${check_h}`,
            },
        });

        if (!response.ok) {
            console.error("Не удалось получить данные:", response.status);
            checkClientData(product_id, product, false);
            return;
        }

        const data = await response.json();

        if (data.email !== localStorage.email) checkClientData(product_id, product, false);
        if (localStorage.first_name && localStorage.last_name && localStorage.phone_number && localStorage.email && localStorage.user_id) {
            checkClientData(product_id, product, true);
        } else checkClientData(product_id, product, false);;

    } catch (error) {
        console.error("Ошибка при получении данных:", error);
    }
}



var checkDataFormForCar = `
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
                        <dt class="col-sm-3">Rang:</dt>
                        <dd class="col-sm-9" id="modalCarColor">error</dd>
                        <dt class="col-sm-3">Yili:</dt>
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
            <div class="col-12 col-lg-6 infoModal">
                <div class="modal-header" style="border:none">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ma'lumotlaringizni tekshiring:</h1>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled mb-0 categories-list">
                        <li>
                            <div class="form-group">
                                <input id="name" type="text" class="form-control" placeholder="ism">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="surname" type="text" class="form-control" placeholder="familya">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="tel" type="number" class="form-control" placeholder="telefon raqam">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="email" type="email" class="form-control" placeholder="email">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="comments" type="text" class="form-control" placeholder="komentariya">
                            </div>
                        </li>
                        <li>
                            <div class="modal-content" style="align-items: center;">
                                <h2>Sanalarni tanlang</h2>
                                <div class="month-header" data-id=''>
                                    <button onclick="prevMonthModal()">&#8592;</button>
                                    <div id="month-name-modal">Месяц</div>
                                    <button onclick="nextMonthModal()">&#8594;</button>
                                </div>
                                <div class="calendar" id="calendar-modal"></div>
                                <br>
                                <div>
                                    <label for="start-date">Dan:</label>
                                    <input type="date" id="start-date" readonly>
                                    <label for="end-date">Gacha:</label>
                                    <input type="date" id="end-date" readonly>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer" style="border:none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
                    <button type="button" class="btn btn-primary" onclick="toOrder()">Buyurtma berish</button>
                </div>
            </div>
            
`
var checkDataFormForTour = `
            <div class="col-12 col-lg-6 infoModal">
                <h3 class="mt-3 mt-lg-0 mb-0" id="modalTourTitle">Error</h3>
                <dl class="row mt-3">
                    <dt class="col-sm-3">from: </dt>
                    <dd class="col-sm-9" id="modalTourFromCountry">error</dd>
                    <dt class="col-sm-3">to: </dt>
                    <dd class="col-sm-9" id="modalTourToCountry">error</dd>
                    <dt class="col-sm-3">Description: </dt>
                    <dd class="col-sm-9" id="modalTourDescription">error</dd>
                    <dt class="col-sm-3">Category:</dt>
                    <dd class="col-sm-9" id="modalTourCategory">error</dd>
                    <dd class="col-sm-9">
                        <a id="modalTourVideo"></a>
                    </dd>
                    <div id="modalTourDepartures">
                    </div>
                </dl>
            </div>
            <div class="col-12 col-lg-6 infoModal">
                <div class="modal-header" style="border:none">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Проверьте ваши данные:</h1>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled mb-0 categories-list">
                        <li>
                            <div class="form-group">
                                <input id="name" type="text" class="form-control" placeholder="name">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="surname" type="text" class="form-control" placeholder="surname">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="tel" type="number" class="form-control" placeholder="number">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="email" type="email" class="form-control" placeholder="email">
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer" style="border:none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="toOrder()">Buyurtma berish</button>
                </div>
            </div>
`
var checkDataFormForHotel = `
			<div class="col-12 col-lg-6 infoModal">
				<div class="product-info-section p-3 hotelModal">
					<h3 style="color:aliceblue;" class="mt-3 mt-lg-0 mb-0" id="modalHotelModel">error</h3>
					<dl class="row mt-3 hotelDatas">
						<dt class="col-sm-6">Xona sig\'imi</dt>
						<dd class="col-sm-6" id="modalHotelCapacity">error</dd>

                        <span class="required-inputs row col-sm-12">
                            <dt class="col-sm-6">Kattalar sig\'imi</dt>
                            <dd class="col-sm-6" id="modalHotelNumAdults" style="padding:0 12px 0 24px;">error</dd>
                        </span>
                        <span class="required-inputs row col-sm-12">
                            <dt class="col-sm-6">Yosh bolalar soni sig\'imi</dt>
                            <dd class="col-sm-6" id="modalHotelNumKids" style="padding:0 12px 0 24px;">error</dd>
                        </span>
                        <span class="required-inputs row col-sm-12">
                            <dt class="col-sm-6">Room type</dt>
                            <dd class="col-sm-6" id="modalHotelRoomType" style="padding:0 12px 0 24px;">error</dd>
                        </span>
                        <span class="required-inputs row col-sm-12">
                            <dt class="col-sm-6">Bed type</dt>
                            <dd class="col-sm-6" id="modalHotelBedType" style="padding:0 12px 0 24px;">error</dd>
                        </span>

						<dt class="col-sm-6">Wi-Fi</dt>
						<dd class="col-sm-6" id="modalHotelWifi">error</dd>
						<dd class="col-sm-6" id="modalHotelAddress">error</dd>
						<dt class="col-sm-6">Yulduzlar</dt>
						<dd class="col-sm-6" id="modalHotelStars">error</dd>
						<dt class="col-sm-6">Joylashuv</dt>
						<dd class="col-sm-6" id="modalHotelLocation">error</dd>
						<dt class="col-sm-6">Xona turi</dt>
						<dt class="col-sm-6">Nonushta</dt>
						<dd class="col-sm-6" id="modalHotelBreakfast">error</dd>
						<dt class="col-sm-6">Sport zali</dt>
						<dd class="col-sm-6" id="modalHotelGym">error</dd>
						<dt class="col-sm-6">Suv havzasi</dt>
						<dd class="col-sm-6" id="modalHotelSwimmingPool">error</dd>
						<dt class="col-sm-6">Avtomobil to'xtatish</dt>
						<dd class="col-sm-6" id="modalHotelParking">error</dd>
						<dt class="col-sm-6">Restoran/Ba'z</dt>
						<dd class="col-sm-6" id="modalHotelRestaurantBar">error</dd>
					</dl>
				</div>
			</div>
            <div class="col-12 col-lg-6 infoModal">
                <div class="modal-header" style="border:none">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Проверьте ваши данные:</h1>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled mb-0 categories-list">
                        <li>
                            <div class="form-group">
                                <input id="name" type="text" class="form-control" placeholder="name">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="surname" type="text" class="form-control" placeholder="surname">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="tel" type="number" class="form-control" placeholder="number">
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <input id="email" type="email" class="form-control" placeholder="email">
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer" style="border:none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="toOrder()">Buyurtma berish</button>
                </div>
            </div>
`

const productModal = {
    'car': checkDataFormForCar,
    'tour': checkDataFormForTour,
    'hotel': checkDataFormForHotel,
}
var numAdults;
var numKids;
var roomType;
var bedType;



function checkClientData(product_id, product, bool) {
    // console.log(model, price, companyName, color, year, seats, fuelType, transmission, deposit, insurance, comment, product, bool)
    var father = document.getElementById('modalToRender')
    if (bool) {
        father.innerHTML = productModal[product];
        if(product === 'car'){
            currentMonth = new Date().getMonth();  // Обновляем месяц
            currentYear = new Date().getFullYear(); // Обновляем год
            selectedStartDate = null;
            selectedEndDate = null;
            document.getElementById('modalCarModel').textContent = foundProduct.model;
            document.getElementById('modalCarPrice').textContent = `Narx: $${foundProduct.price}`;
            document.getElementById('modalCarCompany').textContent = foundProduct.company.name;
            document.getElementById('modalCarColor').textContent = foundProduct.color;
            document.getElementById('modalCarYear').textContent = foundProduct.year;
            document.getElementById('modalCarSeats').textContent = foundProduct.seats;
            document.getElementById('modalCarFuel').textContent = foundProduct.fuel_type;
            document.getElementById('modalCarTransmission').textContent = foundProduct.transmission;
            document.getElementById('modalCarDeposit').textContent = foundProduct.deposit;
            document.getElementById('modalCarInsurance').textContent = foundProduct.insurance;
            document.getElementById('modalCarComment').textContent = foundProduct.comment;
            renderCalendar(currentMonth, currentYear, true);

        }else if(product === 'hotel'){
                    
            father.innerHTML = productModal[product];

            var googleMapsUrl = '';
            var yandexMapLink = '';
            if(foundProduct.location.trim()){
                JSON.parse(foundProduct.location);
                const latitude = foundProduct.location[0] > 90 ? foundProduct.location[0] % 90 : foundProduct.location[0];
                const longitude = foundProduct.location[1] > 180 ? foundProduct.location[1] % 180 : foundProduct.location[1];
                googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
                yandexMapLink = `https://yandex.ru/maps/?ll=${longitude},${latitude}&z=14&l=map`;
            }
            // Настройка модального окна
            document.getElementById('modalHotelModel').textContent = foundProduct.name;
            // document.getElementById('modalHotelPrice').textContent = `Narx: ${foundCmp.price_per_night} so'm`;
            document.getElementById('modalHotelAddress').textContent = `${foundProduct.city} city`;
            document.getElementById('modalHotelStars').textContent = '⭐'.repeat(foundProduct.stars);
            // document.getElementById('modalHotelComments').innerHTML = `${comment}`;
            document.getElementById('modalHotelLocation').innerHTML = `<a href="${googleMapsUrl}" style="color: #3f8dff" target="_blank">Google Xaritalar</a>`;
            document.getElementById('modalHotelWifi').textContent = tf[foundProduct.wifi];
            document.getElementById('modalHotelBreakfast').textContent = tf[foundProduct.breakfast];
            document.getElementById('modalHotelGym').textContent = tf[foundProduct.gym];
            document.getElementById('modalHotelSwimmingPool').textContent = tf[foundProduct.swimming_pool];
            document.getElementById('modalHotelParking').textContent = tf[foundProduct.parking];
            document.getElementById('modalHotelRestaurantBar').textContent = tf[foundProduct.restaurant_bar];
            document.getElementById('modalHotelParking').textContent = tf[foundProduct.parking];

            numAdults = document.getElementById('num_adults').value;
            numKids = document.getElementById('num_kids').value;
            roomType = document.getElementById('room_type').value;
            bedType = document.getElementById('bed_type').value;
            document.getElementById('modalHotelNumAdults').textContent = numAdults;
            document.getElementById('modalHotelNumKids').textContent = numKids;
            document.getElementById('modalHotelCapacity').textContent = +numAdults + +numKids;
            document.getElementById('modalHotelRoomType').textContent = roomType;
            document.getElementById('modalHotelBedType').textContent = bedType;
        }else if(product === 'tour'){
            document.getElementById('modalTourTitle').textContent = foundProduct.title;
            document.getElementById('modalTourDescription').textContent = foundProduct.description;
            document.getElementById('modalTourFromCountry').textContent = foundProduct.fromCountry || foundProduct.from_country;
            document.getElementById('modalTourToCountry').textContent = foundProduct.toCountry || foundProduct.to_country;
            document.getElementById('modalTourCategory').textContent = foundProduct.category;
            foundProduct.departures.forEach(e => {
                var depTime = e.departure_date?.split('').slice(0, 10).join('').split('-').join('.') || e.date.split('').slice(0, 10).join('').split('-').join('.');
                document.getElementById('modalTourDepartures').innerHTML += `
                <div class="d-flex">
                    <p class="col-sm-3">${depTime}</p>
                    <p class="text-success">${e.price}sum</p>
                </div>`;
            })
            
        }

        document.getElementById('name').value = localStorage.first_name;
        document.getElementById('surname').value = localStorage.last_name;
        document.getElementById('tel').value = +localStorage.phone_number;
        document.getElementById('email').value = localStorage.email;
        localStorage.setItem('product', JSON.stringify(product));
    }else{
        father.innerHTML = toLoginForm;
    }
}

function toOrder() {
    var product_api = '';
    var product = JSON.parse(localStorage.product);
    if (product === "car") {
        product_api = `${local_url}/api/order/rco`
        if(selectedStartDate && selectedEndDate){
            orderData.comment = document.getElementById('comments').value + ` ${selectedStartDate} ${selectedEndDate}`;
        }else{
            alert("Выберите дни заказа!!!");
            return;
        }
    }
    if (product === "hotel") {
        product_api = `${local_url}/api/order/hotel`
        if(numAdults && roomType && bedType){
            orderData.num_adults =  numAdults;
            orderData.num_kids =  numKids || 0;
            orderData.room_capacity =  +numAdults + +numKids;
            orderData.room_type =  roomType;
            orderData.bed_type =  bedType;
        }else{
            alert("Заполните обязательные поля!!!");
            document.getElementById('num_adults').style.border = "1px solid #f00 !important";
            document.getElementById('num_kids').style.border = "1px solid #f00 !important";
            document.getElementById('room_type').style.border = "1px solid #f00";
            document.getElementById('bed_type').style.border = "1px solid #f00";
            
            document.querySelectorAll('.required-inputs').forEach(e => {
                e.style.border = "1px solid #f00";
            })
            return;
        }
    }
    if (product === "tour") {
        product_api = `${local_url}/api/order/tour/item`
    }
    orderData.name = document.getElementById('name').value;
    orderData.surname = document.getElementById('surname').value;
    orderData.telephone = document.getElementById('tel').value;
    orderData.user_id = localStorage.user_id;

    const checkDataModal = bootstrap.Modal.getInstance(document.getElementById('QuickViewProductCar'));
    if (checkDataModal) {
        checkDataModal.hide();
    }

    console.log(orderData)
    fetch(product_api, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.access_token}`,
        },
        body: JSON.stringify(orderData)
        }).then(res => {
            if (!res.ok) throw Error(res.statusText);
            return res.json();
        })
        .then(result => {
            // console.log(result)
            console.log(result)
            if (result.id) {

                // Отобразить модальное окно
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                // Дополнительно: перезагрузка страницы после закрытия модального окна
                const modalElement = document.getElementById('successModal');
                modalElement.addEventListener('hidden.bs.modal', () => {
                    window.location.reload();
                });
            }
        })
        .catch(error => console.log(error));

}

