<?php include("./components/header.php"); ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-8">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="user-photo m-b-30">
                                    <img class="img-responsive" id="partner-company-logo" alt="" />
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="user-profile-name" id="partner-name"></div>
                                <div class="user-Location"><i class="ti-location-pin" id="partner-location"></i></div>
                                <div class="user-send-message">
                                    <button class="btn btn-primary btn-addon" id="partner-edit-company-btn" type="button">
                                        <i class="ti-pencil"></i>Edit
                                    </button>
                                    <button class="btn btn-primary btn-addon" id="partner-add-btn" type="button">
                                        <i class="ti-user">+</i>Partner
                                    </button>
                                </div>
                                <div class="user-send-message">
                                    <!-- <button id="connectButton" class="btn btn-success btn-addon" type="button">
                                                        <i id="connectIcon" class="ti-power-on"></i>
                                                        <span id="connectText">Connect</span>
                                                    </button> -->
                                </div>

                                <div class="custom-tab user-profile-tab">
                                    <!-- <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a></li>
                                                    </ul> -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="1">
                                            <div class="contact-information">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Company information</a></li>
                                                </ul>
                                                <div class="phone-content">
                                                    <span class="contact-title">Legal name</span>
                                                    <span class="phone-number" id="partner-legal-name"></span>
                                                </div>
                                                <div class="phone-content">
                                                    <span class="contact-title">Address</span>
                                                    <span class="phone-number" id="partner-company-address"></span>
                                                </div>
                                                <div class="phone-content">
                                                    <span class="contact-title">Category</span>
                                                    <span class="phone-number" id="partner-company-category"></span>
                                                </div>
                                                <div class="phone-content">
                                                    <span class="contact-title">City</span>
                                                    <span class="phone-number" id="partner-company-city"></span>
                                                </div>
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Partner information</a></li>
                                                </ul>
                                                <div class="phone-content">
                                                    <span class="contact-title">Phone:</span>
                                                    <span class="phone-number" id="partner-phone-number"></span>
                                                </div>
                                                <div class="address-content">
                                                    <span class="contact-title">Address:</span>
                                                    <span class="mail-address" id="partner-address"></span>
                                                </div>
                                                <div class="email-content">
                                                    <span class="contact-title">Email:</span>
                                                    <span class="contact-email" id="partner-email"></span>
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

</div>

<div id="popup-wrapper" class="popup-wrapper hidden">
    <div id="popup-background" class="popup-background"></div>
    <div id="popup-content" class="popup-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>Assign Partner</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-16">
                                <div class="basic-form">
                                    <form id="assign-partner-form">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input id="first_name" type="text" name="first_name" class="form-control border-none input-default bg-ash" placeholder="Enter first name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input id="last_name" type="text" name="last_name" class="form-control border-none input-default bg-ash" placeholder="Enter last name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="email" type="email" name="email" class="form-control border-none input-default bg-ash" placeholder="Enter email" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input id="phone_number" type="tel" name="phone_number" class="form-control border-none input-default bg-ash" placeholder="Enter phone number" required>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input id="city" type="text" name="city" class="form-control border-none input-default bg-ash" placeholder="Enter city" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="category" name="category" class="form-control border-none input-default bg-ash" required>
                                                <option value="" disabled selected>Select category</option>
                                                <option value="rentcar">rentcar</option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input id="password" type="password" name="password" class="form-control border-none input-default bg-ash" placeholder="Enter password" required>
                                        </div>
                                        <button id="save-btn" class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="button" onclick="submitForm()">Save</button>
                                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="close-popup" class="btn btn-danger">Close</button>
    </div>
</div>
<!-- anothe hidden block for edit company -->
<div id="popup-wrapper-ec" class="popup-wrapper-ec hidden">
    <div id="popup-background-ec" class="popup-background-ec"></div>
    <div id="popup-content-ec" class="popup-content-ec">
        <div class="row">
            <div class="col-md-12">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>Assign Partner</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-16">
                                <div class="basic-form">
                                    <form id="assign-partner-form-ec">
                                        <div class="form-group">
                                            <label>Legal name</label>
                                            <input id="legal-name" type="text" name="first_name" class="form-control border-none input-default bg-ash" placeholder="Enter company`s legal name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input id="company-name" type="text" name="first_name" class="form-control border-none input-default bg-ash" placeholder="Enter company name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>District</label>
                                            <input id="company-district" type="text" name="" class="form-control border-none input-default bg-ash" placeholder="Enter district" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input id="company-address" type="text" name="last_name" class="form-control border-none input-default bg-ash" placeholder="Enter company`s address" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="company-category" name="category" class="form-control border-none input-default bg-ash">
                                                <option value="rentcar">rentcar</option>
                                                <option value="tur">tur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input id="company-city" type="text" name="city" class="form-control border-none input-default bg-ash" placeholder="Enter city" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Choose new logo or leave it as it is</label>
                                            <input id="logo" type="file" accept="image/*" name="city" class="form-control border-none input-default bg-ash" required>
                                        </div>
                                        <button id="save-btn-ec" class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="button">Save</button>
                                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="close-popup-ec" class="btn btn-danger">Close</button>
    </div>
</div>
<!-- the end of block edit company -->
<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<script>
    function setCompanyLogo(base64Image) {
        const imgElement = document.getElementById('partner-company-logo');
        const defaultImage = 'assets/images/user-profile.jpg';

        if (base64Image) {
            imgElement.src = `data:image/jpeg;base64,${base64Image}`;
        } else {
            imgElement.src = defaultImage;
        }
    }
    // document.getElementById('connectButton').addEventListener('click', function() {
    //     var button = document.getElementById('connectButton');
    //     var icon = document.getElementById('connectIcon');
    //     var text = document.getElementById('connectText');

    //     if (button.classList.contains('btn-success')) {
    //         button.classList.remove('btn-success');
    //         button.classList.add('btn-danger');
    //         icon.classList.remove('ti-power-on');
    //         icon.classList.add('ti-power-off');
    //         text.textContent = 'Disconnect';
    //     } else {
    //         button.classList.remove('btn-danger');
    //         button.classList.add('btn-success');
    //         icon.classList.remove('ti-power-off');
    //         icon.classList.add('ti-power-on');
    //         text.textContent = 'Connect';
    //     }
    // });
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".user-avatar").textContent = localStorage.getItem("first_name")
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');
        var logo = ""
        fetch(`/api/admin/company/${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            })
            .then(resp => resp.json())
            .then(data => {
                document.getElementById("partner-legal-name").innerText = data.company.legal_name;
                document.getElementById("partner-company-address").innerText = data.company.address;
                document.getElementById("partner-company-category").innerText = data.company.category;
                document.getElementById("partner-company-city").innerText = data.company.city;


                document.getElementById("legal-name").value = data.company.legal_name;
                document.getElementById("company-name").value = data.company.name;
                document.getElementById("company-address").value = data.company.address;
                document.getElementById("company-category").value = data.company.category;
                document.getElementById("company-city").value = data.company.city;
                document.getElementById("company-district").value = data.company.district;


                document.getElementById("partner-location").innerText = data.users[0].city;
                document.getElementById("partner-phone-number").innerText = data.users[0].phone_number ? data.users[0].phone_number : "none";
                document.getElementById("partner-address").innerText = data.users[0].city ? data.users[0].city : "none";
                document.getElementById("partner-email").innerText = data.users[0].email ? data.users[0].email : "none";
                document.getElementById("partner-name").innerText = data.users[0].first_name + " " + data.users[0].last_name ? data.users[0].first_name : "none";
                setCompanyLogo(data.company.logo)
            })
            .catch(err => {
                console.log(err)
            })
    })
</script>

<?php include("./components/footer.php"); ?>