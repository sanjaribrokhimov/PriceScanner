<?php include './components/header.php' ?>


            
            <div id="main-content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Users</div>

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



<!-- <script>
    const fetchUrl = `${local_url}/api/hotel/hotel/${localStorage.company_id}/rooms`;
    fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.access_token}`, // Добавляем заголовок с токеном
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.rooms && data.rooms.length > 0) {
                console.log(data.rooms);
                console.log(data.pages)
            } else {
                alert('No rooms found for this hotel.');
            }
        })
        .catch(error => {
            console.error('Error fetching rooms:', error);
        });
</script> -->


<?php include './components/footer.php' ?>



