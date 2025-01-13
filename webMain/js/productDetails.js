
async function showCarDetails(model, price, companyName, image, color, year, seats, fuelType, transmission, deposit, insurance, comment, carId) {
    document.getElementById('modalToRender').innerHTML = carModalBody;

    // Modal oynaga qiymatlarni o'rnatish
    document.getElementById('modalCarModel').textContent = model;
    document.getElementById('modalCarPrice').textContent = `Narx: $${price}`;
    document.getElementById('modalCarCompany').textContent = companyName;
    document.getElementById('modalCarColor').textContent = color;
    document.getElementById('modalCarYear').textContent = year;
    document.getElementById('modalCarSeats').textContent = seats;
    document.getElementById('modalCarFuel').textContent = fuelType;
    document.getElementById('modalCarTransmission').textContent = transmission;
    document.getElementById('modalCarDeposit').textContent = deposit;
    document.getElementById('modalCarInsurance').textContent = insurance;
    document.getElementById('modalCarComment').textContent = comment;

    // Karuselni tozalash va yangilash
    const gallery = $('.product-gallery');
    gallery.trigger('destroy.owl.carousel');
    gallery.find('.owl-stage-outer').children().unwrap();

    // Yangi tasvir qo'shish
    gallery.html(`
            <div class="item">
                <img id="modalCarImage" class="img-fluid" src="data:image/png;base64,${image}" alt="Tasvir">
            </div>
        `);

    gallery.owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        dots: false
    });

    let headers = new Headers({
        'Authorization': `Bearer ${localStorage.access_token}`,
        'Content-Type': 'application/json' // или другие заголовки, если необходимо
    });
    
    await fetch(`${local_url}/api/rentcar/companies/web_main/car/${carId}`, {
      method: 'GET',
      headers: headers
    })
        .then(response => response.json())
        .then(data => {
            // Рендерим карточки
            console.log(data)
            // Отображаем пагинацию
        })
        .catch(error => console.error('Error fetching data:', error));
    let car = cars.find(item => +item.id === +carId);
    unavailableDates = car.availability;
    
    currentMonth = new Date().getMonth();  // Обновляем месяц
    currentYear = new Date().getFullYear(); // Обновляем год
    selectedStartDate = null;
    selectedEndDate = null;

    renderCalendar(currentMonth, currentYear, false);
}




function showHotelDetails(name, address, stars, comments, location, wifi, breakfast, gym, swimmingPool, parking, restaurantBar, images) {
    document.getElementById('modalToRender').innerHTML = hotelModalBody;

    console.log(images)
    images = images.split(',');
    
    const gallery = $('.product-gallery');
    gallery.trigger('destroy.owl.carousel'); // Уничтожаем старый карусель
    gallery.find('.owl-stage-outer').children().unwrap(); // Убираем обертки

    // Добавляем новые изображения
    let carouselResult = '';
    images.forEach(e => {
        carouselResult += `
            <div class="item">
                <img id="modalCarImage" class="img-fluid" src="${local_url}/api/hotel/${e}" alt="Tasvir">
            </div>
        `;
    });
    gallery.html(carouselResult);

    // Запускаем новый карусель
    gallery.owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        dots: true,
    });

    var googleMapsUrl = '';
    var yandexMapLink = '';
    if(location.trim()){
        JSON.parse(location);
        const latitude = location[0] > 90 ? location[0] % 90 : location[0];
        const longitude = location[1] > 180 ? location[1] % 180 : location[1];
        googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
        yandexMapLink = `https://yandex.ru/maps/?ll=${longitude},${latitude}&z=14&l=map`;
    }

    let comment = '';
    comments.split(',').forEach(e => {
        comment += `<p>${e}</p>`;
    });

    // Настройка модального окна
    document.getElementById('modalHotelModel').textContent = name;
    document.getElementById('modalHotelAddress').textContent = address;
    document.getElementById('modalHotelStars').textContent = '⭐'.repeat(stars);
    // document.getElementById('modalHotelComments').innerHTML = `${comment}`;
    document.getElementById('modalHotelLocation').innerHTML = `<a href="${googleMapsUrl}" style="color: #3f8dff" target="_blank">Google Xaritalar</a>`;
    document.getElementById('modalHotelWifi').textContent = tf[wifi];
    document.getElementById('modalHotelBreakfast').textContent = tf[breakfast];
    document.getElementById('modalHotelGym').textContent = tf[gym];
    document.getElementById('modalHotelSwimmingPool').textContent = tf[swimmingPool];
    document.getElementById('modalHotelParking').textContent = tf[parking];
    document.getElementById('modalHotelRestaurantBar').textContent = tf[restaurantBar];
    document.getElementById('modalHotelParking').textContent = tf[parking];

}



// Tur tafsilotlarini ko‘rsatish funksiyasi

function showTourDetails(tourId) {
    const tour = tours.find(item => item.id === tourId);
    if (tour) {
        document.getElementById('modalToRender').innerHTML = tourModalBody;

        // Обновляем заголовок тура
        document.getElementById('modalTourTitle').textContent = tour.title;

        // Добавляем Sanalar va narxlar
        let departuresHTML = '<h5>Sanalar va narxlar</h5>';
        tour.departures.forEach(departure => {
            const formattedDate = departure.departure_date.replace('T00:00:00', ' ');
            departuresHTML += `
                <div class="departure-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="departure-date text-light fs-6">${formattedDate.trim()}</span>
                        <span class="departure-price text-success fs-5 fw-bold">$${departure.price}</span>
                    </div>
                </div>
            `;
        });
        document.getElementById('modalTourDepartures').innerHTML = departuresHTML;

        // Описание тура
        document.getElementById('modalTourDescription').textContent = tour.description;

        // Добавляем информацию о категории, стране отправления и стране назначения
        const categoryHTML = `
            <div class="tour-details mt-3">
                <p class="text-light fs-6">kategoriya: <span class="text-info">${tour.category}</span></p>
                <p class="text-light fs-6">dan: <span class="text-info">${tour.fromCountry}</span></p>
                <p class="text-light fs-6">ga: <span class="text-info">${tour.toCountry}</span></p>
            </div>
        `;
        document.getElementById('modalTourDescription').insertAdjacentHTML('beforeend', categoryHTML);

        // Добавляем изображения тура
        const gallery = $('.product-gallery');
        gallery.trigger('destroy.owl.carousel'); // Уничтожаем карусель
        gallery.find('.owl-stage-outer').children().unwrap(); // Убираем обертку

        // Устанавливаем новое изображение
        let carouselResult = ''
        tour.images.forEach(e => {
            carouselResult += `
                <div class="item">
                    <img id="modalCarImage" class="img-fluid" src="${local_url}/api/tour/${e}" alt="Tasvir">
                </div>
            `
        })
        gallery.html(carouselResult);

        // Инициализируем карусель заново
        gallery.owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: true,
        });

        // Обновляем цену
        updatePrice(tour.departures[0].price);
    }
}










// Функция для генерации календаря
function renderCalendar(month, year, isModal = false) {
    const calendar = isModal
      ? document.getElementById("calendar-modal")
      : document.querySelector(".calendar-for-date");
    const monthName = isModal
      ? document.getElementById("month-name-modal")
      : document.querySelector(".month-name-for-date");
  
    calendar.innerHTML = ""; // Очищаем старый календарь
    monthName.textContent = `${months[month]} ${year}`; // Обновляем название месяца
  
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const daysInMonth = lastDayOfMonth.getDate();
    const startDay = firstDayOfMonth.getDay(); // День недели для первого числа месяца
  
    const daysOfWeek = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
    daysOfWeek.forEach((day) => {
      const dayElement = document.createElement("div");
      dayElement.textContent = day;
      calendar.appendChild(dayElement);
    });
  
    for (let i = 0; i < startDay; i++) {
      calendar.appendChild(document.createElement("div"));
    }
  
    for (let i = 1; i <= daysInMonth; i++) {
      const dayElement = document.createElement("div");
      dayElement.classList.add("day");
      dayElement.textContent = i;
  
      const currentDate = `${year}-${(month + 1).toString().padStart(2, "0")}-${i
        .toString()
        .padStart(2, "0")}`;
      if (isUnavailable(currentDate)) {
        dayElement.classList.add("unavailable");
      }
  
      if (selectedStartDate && selectedEndDate) {
        if (currentDate === selectedStartDate) {
          dayElement.classList.add("selected-start");
        } else if (currentDate === selectedEndDate) {
          dayElement.classList.add("selected-end");
        } else if (currentDate > selectedStartDate && currentDate < selectedEndDate) {
          dayElement.classList.add("in-range");
        }
      }
  
      // Проверка, можно ли выбрать текущую дату
      dayElement.onclick = () => {
        if (!dayElement.classList.contains("unavailable")) {
          handleDateSelection(currentDate);
        }
      };
      calendar.appendChild(dayElement);
    }
  }
  
  // Проверяет, доступна ли конкретная дата
  function isUnavailable(date) {
    return unavailableDates.some((range) => {
      const start = range.start_date;
      const end = range.end_date;
      return date >= start && date <= end;
    });
  }
  
  // Проверяет, пересекается ли выбранный диапазон с занятыми датами
  function isDateInRange(startDate, endDate) {
    return unavailableDates.some((range) => {
      const rangeStart = new Date(range.start_date);
      const rangeEnd = new Date(range.end_date);
      const selectedStart = new Date(startDate);
      const selectedEnd = new Date(endDate);
  
      return (
        (selectedStart >= rangeStart && selectedStart <= rangeEnd) || // Начало диапазона попадает в занятые
        (selectedEnd >= rangeStart && selectedEnd <= rangeEnd) || // Конец диапазона попадает в занятые
        (selectedStart <= rangeStart && selectedEnd >= rangeEnd) // Диапазон полностью перекрывает занятые даты
      );
    });
  }
  
  // Обработчик выбора даты
  function handleDateSelection(date) {
    if (!selectedStartDate) {
      if (isUnavailable(date)) {
        alert("Эта дата недоступна для выбора.");
        return;
      }
      selectedStartDate = date;
      document.getElementById("start-date").value = selectedStartDate;
      document.getElementById("end-date").value = ""; // Сбросить end_date
    } else if (!selectedEndDate) {
      if (date < selectedStartDate) {
        selectedEndDate = selectedStartDate;
        selectedStartDate = date;
      } else {
        selectedEndDate = date;
      }
  
      if (isDateInRange(selectedStartDate, selectedEndDate)) {
        alert("Выбранный диапазон содержит недоступные даты.");
        selectedStartDate = null;
        selectedEndDate = null;
        document.getElementById("start-date").value = "";
        document.getElementById("end-date").value = "";
        return;
      }
  
      document.getElementById("start-date").value = selectedStartDate;
      document.getElementById("end-date").value = selectedEndDate;
    } else {
      // Если обе даты выбраны, сбросить диапазон
      if (isUnavailable(date)) {
        alert("Эта дата недоступна для выбора.");
        return;
      }
      selectedStartDate = date;
      selectedEndDate = null;
      document.getElementById("start-date").value = selectedStartDate;
      document.getElementById("end-date").value = "";
    }
  
    renderCalendar(currentMonth, currentYear, true); // Обновляем календарь в модальном окне
  }


    
    function nextMonth() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        renderCalendar(currentMonth, currentYear, false);
    }

    function prevMonth() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        renderCalendar(currentMonth, currentYear, false);
    }
    
    function nextMonthModal() {
        let index = document.querySelector('.month-header').dataset.id;

        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        renderCalendar(currentMonth, currentYear, true);
    }

    function prevMonthModal() {
        let index = document.querySelector('.month-header').dataset.id;
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        renderCalendar(currentMonth, currentYear, true);
    }

