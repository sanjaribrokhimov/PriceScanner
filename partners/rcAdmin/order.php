<?php include './components/header.php' ?>



<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <script>
                            // Функция для получения параметров из URL
                            function getQueryParams() {
                                const queryString = window.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                return Object.fromEntries(urlParams.entries());
                            }

                            const hrefParams = getQueryParams();
                        </script>

                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editCar()">
                                <i class="ti-pencil"></i>Edit
                            </button>

                            <script>
                                function editCar() {
                                    const carId = localStorage.getItem('car_id');
                                    if (carId) {
                                        window.location.href = `editCar.php?car_id=${carId}`;
                                    } else {
                                        alert('Car ID not found');
                                    }
                                }
                            </script>
                        </div>

                        <br><br>
                        <div class="col-lg-8">
                            <div class="user-profile-name" id="carModel">{model}</div>
                            <div class="contact-information">
                                <div class="phone-content">
                                    <span class="contact-title">Ismi:</span>
                                    <span id="name">{ismi} sum</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<script>
    
</script>


<?php include './components/footer.php' ?>