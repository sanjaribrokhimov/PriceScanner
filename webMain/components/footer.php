

<!-- Modal for success order -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" style="z-index: 10001">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Muvaffaqiyat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Buyurtma muvaffaqiyatli qo'shildi. Tez orada operatorlarimiz siz bilan bog'lanadi!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ок</button>
            </div>
        </div>
    </div>
</div>



<footer>
    <section class="py-4 bg-dark-1">
        <div class="container">
            <div class="for-ico-foot">

            <!-- RENDER SOCIAL ICO -->
            </div>
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                <div class="col">
                    <div class="footer-section1 mb-3">
                        <h6 class="mb-3 text-uppercase">Aloqa ma'lumotlari</h6>
                        <div class="address mb-3">
                            <p class="mb-0 text-uppercase text-white">Manzil</p>
                            <p class="mb-0 font-12">123 Street Name, City, Australia</p>
                        </div>
                        <div class="phone mb-3">
                            <p class="mb-0 text-uppercase text-white">Telefon</p>
                            <p class="mb-0 font-13">Toll Free (123) 472-796</p>
                            <p class="mb-0 font-13">Mobil : +91-9910XXXX</p>
                        </div>
                        <div class="email mb-3">
                            <p class="mb-0 text-uppercase text-white">Email</p>
                            <p class="mb-0 font-13">mail@example.com</p>
                        </div>
                        <div class="working-days mb-3">
                            <p class="mb-0 text-uppercase text-white">ISH KUNLARI</p>
                            <p class="mb-0 font-13">Du - Ju / 9:30 - 18:30</p>
                        </div>
                    </div>
                </div>
               

                <div class="col">
                    <div class="footer-section4 mb-3">
                        <h6 class="mb-3 text-uppercase">Xabardor bo'ling</h6>
                        <div class="subscribe">
                            <input type="text" class="form-control radius-30" placeholder="Enter Your Email" />
                            <div class="mt-2 d-grid"> <a href="javascript:;"
                                    class="btn btn-white btn-ecomm radius-30">Obuna boʻling</a>
                            </div>
                            <p class="mt-2 mb-0 font-13">Erta chegirmali takliflar, yangilanishlar va yangi mahsulotlar haqida ma'lumot olish uchun axborot byulletenimizga obuna bo'ling.</p>
                        </div>
                        <div class="download-app mt-3">
                            <h6 class="mb-3 text-uppercase">Ilovamizni yuklab oling</h6>
                            <div class="d-flex align-items-center gap-2">
                                <a href="javascript:;">
                                    <img src="./image/demo.png" class="" width="160" alt="" />
                                </a>
                                <a href="javascript:;">
                                    <img src="./image/demo.png" class="" width="160" alt="" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
            <hr />
            
            <!--end row-->
        </div>
    </section>
</footer>

<div class="modal-overlay"></div>
<div class="review-modal">
  <form class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Оставить отзыв</h2>
    <p>Buyurtma ID: <span class="order-id"></span></p>
    <label>
      Baho (1-10):
      <div class="stars">
        <!-- Создаем 10 звёзд -->
        <span data-value="1">&#9733;</span>
        <span data-value="2">&#9733;</span>
        <span data-value="3">&#9733;</span>
        <span data-value="4">&#9733;</span>
        <span data-value="5">&#9733;</span>
        <span data-value="6">&#9733;</span>
        <span data-value="7">&#9733;</span>
        <span data-value="8">&#9733;</span>
        <span data-value="9">&#9733;</span>
        <span data-value="10">&#9733;</span>
      </div>
      <input type="hidden" class="stars-input" name="rating" value="0">
    </label>
    <label>
      Izoh:
      <textarea class="comment-input" required></textarea>
    </label>
    <button class="submit-review">Junatish</button>
  </form>
</div>


<!--end footer section-->
<!--start quick view product-->
<!-- Modal -->

<!--end quick view product-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
</div>
<!--end wrapper-->
<!--start switcher-->



<!-- External Scripts -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
<script src="assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/index.js"></script>
<script>
var menu = document.getElementById("menu3");
var mT = menu.offsetTop;
window.onscroll = function() {
    var menu = document.getElementById("menu3");
    var Y = window.scrollY;
    if (mT < Y) {
        menu.classList.add("fixed");
        menu.style.padding = '0'
    } else {
        menu.classList.remove("fixed");
        menu.style.padding = '15px 0'
    }
};

const starsContainer = document.querySelector('.stars');
const stars = document.querySelectorAll('.stars span');
const starsInput = document.querySelector('.stars-input');

stars.forEach((star) => {
  star.addEventListener('click', () => {
    // Устанавливаем значение при клике
    starsInput.value = star.dataset.value;
    highlightStars(star.dataset.value);
    console.log(star.dataset.value)
  });
});

function highlightStars(rating) {
  stars.forEach((star) => {
    if (+star.dataset.value <= +rating) {
      star.classList.add('active');
    } else {
      star.classList.remove('active');
    }
  });
}

function resetStars() {
  stars.forEach((star) => star.classList.remove('active'));
}


// document.querySelector('#adaptive-menu-bar').onclick = function() {
//     document.querySelector('#menu3>.container').classList.toggle('hide')
// }

document.getElementById('search-data').addEventListener('click', function(e) {
    e.preventDefault(); // Предотвращаем перезагрузку страницы

    // Получаем значение из поля ввода
    const query = document.getElementById('query').value;
    const category = document.getElementById('category').value;

    // Перенаправляем на searched.html с параметром query
    window.location.href = `searched.php?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`;
});


//user zakazlarini chiqarish kere
const cartList = document.querySelector('.cart-list');

// Функция для получения данных заказов
async function fetchUserOrders() {
  try {
    const response = await fetch(`${local_url}/api/order/user-orders`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("access_token")}`, // Добавить токен авторизации
      },
    });

    if (response.status === 200) {
      const data = await response.json();
      renderOrders(data.orders);
    //   console.log(data)
    } else if (response.status === 404) {
      console.error("Пользователь не найден.");
    } else {
      console.error("Произошла ошибка при получении данных.");
    }
  } catch (error) {
    console.error("Ошибка запроса:", error);
  }
}
// Функция для рендера заказов
async function renderOrders(orders) {
    let cartList = document.querySelector(".cart-list");
    cartList.innerHTML = ""; // Очищаем список перед рендерингом

    let fragment = document.createDocumentFragment(); // Используем фрагмент для оптимизации

    for (const order of orders) {
        let listItem = document.createElement("li");

        try {
            let productInfo = await getProductInfo(order);
            // console.log(productInfo)

            let productName = order.type === 'rentcar' ? productInfo.car.model : order.type === 'tour' ? productInfo.tour.title : ''
            let companyName = order.type === 'rentcar' ? `
                    <span class="col-12 text-align-right">
                        <strong>Компания:</strong> ${productInfo.company.name || "Неизвестно"}
                    </span>
            ` : '';

            // Кнопка "isCompleted" рендерится только для заказов со статусом "Closed"
            let isCompleted = "";
            if (order.status === "Closed") {
                isCompleted = `
                <button class="is-completed-btn" data-order-id="${order.company_id}">
                    Оставить отзыв
                </button>
                `;
            }

            listItem.innerHTML = `
                <div class="s-order row">
                <div class="col-6">
                    <div class="row">
                    <span class="col-12">
                        <h4>${productName || "Неизвестно"}</h4>
                    </span>
                    <span class="col-12">
                        <p class="order_type">${order.type || "Неизвестно"}</p>
                    </span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                    <span class="col-12 text-align-right">
                        <strong>Статус:</strong> <span class="stat ${order.status === "Viewed" ? "good" : "bad"}"></span>${order.status}
                    </span>
                    ${companyName}
                    </div>
                </div>
                <div class="row">
                    <span class="col-6">
                    ${new Date(order.created_at).toLocaleString()}
                    </span>
                    <span class="col-6 text-align-right">
                    ${isCompleted}
                    </span>
                </div>
                </div>
            `;
            fragment.appendChild(listItem);
        } catch (error) {
            console.error(`Error fetching product info for order ${order.id}:`, error);
            listItem.innerHTML = `
                <div class="s-order row">
                <div class="col-12">
                    <p>Ошибка загрузки информации о продукте для заказа ID: ${order.id}</p>
                </div>
                </div>
            `;
            fragment.appendChild(listItem);
        }
    }

    // Добавляем все элементы в DOM за один раз
    cartList.appendChild(fragment);

    ifIsMobileVersion();

    // Добавляем обработчик на кнопки "isCompleted"
    document.querySelectorAll(".is-completed-btn").forEach((button) => {
        button.addEventListener("click", (event) => {
            const orderId = event.target.getAttribute("data-order-id");
            openReviewModal(orderId);
        });
    });
}

// Функция для открытия модального окна
function openReviewModal(orderId, textContent = "Fikr qoldiring") {
    const modal = document.querySelector(".review-modal");
    const overlay = document.querySelector(".modal-overlay");

    modal.querySelector('h2').textContent = textContent;
    if(textContent === "Platformamizni baholang"){
      document.querySelector('.review-modal p').style.display = "none"
    }else{
      document.querySelector('.review-modal p').style.display = "inline-block"
    }

    modal.querySelector(".order-id").textContent = orderId;

    // Показываем модальное окно и overlay
    modal.classList.add("active");
    overlay.classList.add("active");

    // Отключаем прокрутку страницы
    document.body.classList.add("no-scroll");

    // Закрытие модального окна
    overlay.addEventListener("click", closeModal);
    modal.querySelector(".close-modal").addEventListener("click", closeModal);

    function closeModal() {
        modal.classList.remove("active");
        overlay.classList.remove("active");
        modal.querySelector('form').reset();
        // Включаем прокрутку страницы
        document.body.classList.remove("no-scroll");
    }

    // Отправка данных отзыва
    modal.querySelector(".submit-review").addEventListener("click", async (e) => {
        e.preventDefault()
        const stars = modal.querySelector(".stars-input").value;
        const comment = modal.querySelector(".comment-input").value;

        // Проверяем, что данные заполнены
        if (!stars || !comment) {
          alert("Пожалуйста, заполните все поля!");
          return;
        }

        const payload = {
            company_id: orderId, // ID заказа
            stars: parseInt(stars, 10), // Преобразуем оценку в число
            comment, // Текст комментария
        };
        // console.log(payload)

        try {
          // Отправка данных на API
          const response = await fetch(`${local_url}/api/rating/item`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Authorization": `Bearer ${localStorage.getItem("access_token")}`, // Добавить токен авторизации
            },
            body: JSON.stringify(payload),
          });

          if (response.ok) {
            const data = await response.json();
            // console.log("Успешно отправлено:", data);
            alert("Ваш отзыв успешно отправлен!");
            closeModal();
          } else {
            console.error("Ошибка при отправке:", response.statusText);
            alert("Ошибка при отправке отзыва. Пожалуйста, попробуйте снова.");
          }
        } catch (error) {
          console.error("Ошибка:", error);
          alert("Произошла ошибка при отправке отзыва. Проверьте подключение.");
        }
        if(+orderId === 1){
            try {
              let responseAfter = await fetch(`${local_url}/api/auth/set-false`, {
                method: "PUT",
                headers: {
                  "Content-Type": "application/json",
                  "Authorization": `Bearer ${localStorage.getItem("access_token")}`, // Токен авторизации
                }
              });

              if (responseAfter.ok) {
                const data = await responseAfter.json();
                // console.log("Успешный запрос:", data);
              } else {
                console.error("Ошибка при выполнении запроса:", responseAfter.status, responseAfter.statusText);
              }
          } catch (error) {
              console.error("Ошибка сети или другие проблемы:", error);
          }
        }
    });
}




// Функция для получения информации о продукте
async function getProductInfo(order) {
  try {
    switch (order.type) {
      case "tour": {
        const response = await fetch(`${local_url}/api/tour/item/${order.tour_id}`);
        if (!response.ok) throw new Error(`Error fetching tour info: ${response.status}`);
        return await response.json();
      }
      case "rentcar": {
        const response = await fetch(`${local_url}/api/rentcar/companies/web_main/car/${order.car_id}`);
        if (!response.ok) throw new Error(`Error fetching car info: ${response.status}`);
        return await response.json();
      }
      case "hotel": {
        const response = await fetch(`${local_url}/api/hotel/items/${order.hotel_id}`);
        if (!response.ok) throw new Error(`Error fetching hotel info: ${response.status}`);
        return await response.json();
      }
      default:
        return {};
    }
  } catch (error) {
    console.error(error);
    return { error: error.message };
  }
}


</script>



<script>
// Функция для изменения языка
function changeLanguage(lang) {
    // Сохраняем выбранный язык
    localStorage.setItem('selectedLanguage', lang);
    
    // Обновляем текст на кнопке выбора языка
    const langDisplay = {
        'uz': 'UZB',
        'ru': 'RU',
        'en': 'ENG'
    };
    document.getElementById('current-lang').textContent = langDisplay[lang];
    
    // Отправляем AJAX запрос для получения переводов
    fetch(`api/get_translations.php?lang=${lang}`)
        .then(response => response.json())
        .then(translations => {
            // Обновляем все переводимые элементы на странице
            updatePageTranslations(translations);
        });
}

// Функция обновления переводов на странице
function updatePageTranslations(translations) {
    // Обновляем placeholder поиска
    document.getElementById('query').placeholder = translations.search;
    
    // Обновляем опции в селекте категорий
    const categorySelect = document.getElementById('category');
    categorySelect.options[0].text = translations.hotel;
    categorySelect.options[1].text = translations.car;
    categorySelect.options[2].text = translations.tour;
    
    // Обновляем другие элементы с переводами
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[key]) {
            element.textContent = translations[key];
        }
    });
}


function ifIsMobileVersion(){
    let winW = window.outerWidth;
    // console.log('mobil')
    if(winW < 600){
        var cartListForMobile = document.getElementById('forMobile')
        cartListForMobile.style.width = `${window.outerWidth}px`
        let boundingElement = cartListForMobile.getBoundingClientRect();
        // console.log(cartListForMobile, boundingElement)
        cartListForMobile.style.left = `-${boundingElement.left}px`;

        document.querySelector('.for-ico-foot').innerHTML += `
            <ul class="navbar-nav social-link  ms-auto" style="flex-direction: row; margin: 10px 0">
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-facebook"></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-instagram"></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-telegram"></i></a>
                </li>
            </ul>
        `
    }
    if(winW > 600){
        document.querySelector('.to-social-links').innerHTML += `
            <ul class="navbar-nav social-link  ms-auto">
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-facebook"></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-instagram"></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class="bx bxl-telegram"></i></a>
                </li>
            </ul>
        `
    }
}

function fetchUserOrdersWithDelay() {
  setTimeout(() => {
    fetchUserOrders();
  }, 1000); // Задержка в 1000 мс (1 секунда)
}


async function sendVisitRequest(token) {
  try {
    const response = await fetch(`${local_url}/api/auth/visit`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${token}`, // Токен авторизации
      }
    });

    if (response.ok) {
      const data = await response.json();
    //   console.log("Успешный запрос:", data);
    } else {
      console.error("Ошибка при выполнении запроса:", response.status, response.statusText);
    }
  } catch (error) {
    console.error("Ошибка сети или другие проблемы:", error);
  }
}


async function feedbackEligible(token) {
    // console.log('eliagelbe')
    try {
        const response = await fetch(`${local_url}/api/auth/feedback-eligible`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`, // Токен авторизации
        }
        });

        if (response.ok) {
            const data = await response.json();
            if(data.eligible){
                openReviewModal(1, "Platformamizni baholang")
            }
        } else {
            console.error("Ошибка при выполнении запроса:", response.status, response.statusText);
        }
    } catch (error) {
        console.error("Ошибка сети или другие проблемы:", error);
    }
}



// При загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    
    fetchCars(); // Загружаем данные cars, tours, hotels
    fetchTours(); 
    fetchHotels();
    // Получаем сохраненный язык или используем узбекский по умолчанию
    const savedLang = localStorage.getItem('selectedLanguage') || 'uz';
    changeLanguage(savedLang);
    fetchUserOrdersWithDelay()
    let authToken = localStorage.getItem('access_token');

    if(authToken){
        feedbackEligible(authToken);

        setTimeout(() => {
            sendVisitRequest(authToken);
        }, 5000); // Задержка в 5 секунд
    }
});
</script>
</body>

</html>