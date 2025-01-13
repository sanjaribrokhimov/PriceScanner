<?php include './components/header.php' ?>

            
            <div id="main-content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Products</div>

                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-up color-success"></i>
                                        <span class="stat-digit"> 0</span>
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary w-70" role="progressbar"
                                        aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                        <span class="sr-only">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="main-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card alert">
                                    <div class="card-header">
                                        <h4>Calender</h4>
                                        <div class="card-header-right-icon">
                                            <ul>
                                                <li class="card-close" data-dismiss="alert"><i class="ti-close"></i>
                                                </li>
                                                <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="year-calendar"></div>
                                    </div>
                                </div>
                                <!-- /# card -->
                            </div>
                            <!-- /# column -->
                        </div>
                        <!-- /# row -->

                        
<script>
    var total = document.querySelector('.stat-digit')
    var companyId = localStorage.getItem('company_id')
    var token = localStorage.getItem('access_token')
    fetch(`${local_url}/api/rentcar/companies/${companyId}/cars`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === false) {
            window.location.href = 'login.php';
        } else {
            total.textContent = data.total
        }
    })
    .catch(error => {
        console.error('Ошибка при загрузке данных:', error);
        window.location.href = 'login.php';
    });
</script>

<?php include './components/footer.php' ?>



