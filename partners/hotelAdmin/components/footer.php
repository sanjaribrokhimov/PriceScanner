<div class="row">
    <div class="col-lg-12">
        <div class="footer">
            <p>This dashboard was generated on <span id="date-time"></span> <a href="#"
                    class="page-refresh">Refresh Dashboard</a></p>
        </div>
    </div>
</div>
</div>



<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>


</div>

<script>

    var category = localStorage.getItem('category');
    if(category !== 'hotel'){
        localStorage.clear();
        // Перенаправляем на страницу входа
        window.location.href = '../login.php';
    }

    document.addEventListener("DOMContentLoaded", function() {
        fetch(`${local_url}/api/auth/check-health`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.message) {
                    window.location.href = '../login.php';
                }
            })
            .catch(error => {
                window.location.href = '../login.php';
            });

    })
    document.addEventListener('DOMContentLoaded', function() {
        // Получаем данные из localStorage

        document.getElementById('user-name').innerText = `${localStorage.getItem('first_name')} ${localStorage.getItem('last_name')}`;


        // Обработчик для кнопки Logout
        const logoutButtons = document.querySelectorAll('a[href="../login.php"]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                // Очищаем localStorage
                localStorage.clear();
                // Перенаправляем на страницу входа
                window.location.href = '../login.php';
            });
        });
    });
</script>
<script src="assets/js/lib/jquery.min.js"></script>
<!-- jquery vendor -->
<script src="assets/js/lib/jquery.nanoscroller.min.js"></script>
<!-- nano scroller -->
<script src="assets/js/lib/menubar/sidebar.js"></script>
<script src="assets/js/lib/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="assets/js/lib/bootstrap.min.js"></script>
<!-- bootstrap -->
<script src="assets/js/lib/weather/jquery.simpleWeather.min.js"></script>
<script src="assets/js/lib/weather/weather-init.js"></script>
<script src="assets/js/lib/circle-progress/circle-progress.min.js"></script>
<script src="assets/js/lib/circle-progress/circle-progress-init.js"></script>
<script src="assets/js/lib/chartist/chartist.min.js"></script>
<script src="assets/js/lib/chartist/chartist-init.js"></script>
<script src="assets/js/lib/sparklinechart/jquery.sparkline.min.js"></script>
<script src="assets/js/lib/sparklinechart/sparkline.init.js"></script>
<script src="assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
<script src="assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
<script src="assets/js/scripts.js"></script>




<!-- jquery vendor -->
<script src="assets/js/lib/jquery.min.js"></script>
<script src="assets/js/lib/jquery.nanoscroller.min.js"></script>

<script src="assets/js/lib/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="assets/js/lib/bootstrap.min.js"></script>
<!-- bootstrap -->



<script src="assets/js/lib/calendar-2/moment.latest.min.js"></script>
<!-- scripit init-->
<script src="assets/js/lib/calendar-2/semantic.ui.min.js"></script>
<!-- scripit init-->
<script src="assets/js/lib/calendar-2/prism.min.js"></script>
<!-- scripit init-->
<script src="assets/js/lib/calendar-2/pignose.calendar.min.js"></script>
<!-- scripit init-->
<script src="assets/js/lib/calendar-2/pignose.init.js"></script>
<!-- scripit init-->
<script src="assets/js/scripts.js"></script>
<!-- scripit init-->
</body>

</html>