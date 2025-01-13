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
                                    <button class="btn btn-primary btn-danger" id="partner-edit-tg-chat" type="button">
                                        <i class="ti-tumblr">+</i>Add tg chat
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
                                                <option value="tour">tour</option>
                                                <option value="hotel">hotel</option>
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
                            <h4>Edit Company</h4>
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
                                                <option value="tour">tour</option>
                                                <option value="hotel">hotel</option>
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

<div id="popup-wrapper-tg" class="popup-wrapper hidden">
    <div id="popup-background-tg" class="popup-background"></div>
    <div id="popup-content-tg" class="popup-content" style="position:relative;">
        <div style="position:absolute; top:10px;right:10px; z-index: 10">
            <button id="close-popup-tg" class="btn btn-danger">Close</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>Add telegram chat</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-16">
                                <div class="basic-form">
                                    <form id="assign-partner-form-tg">
                                        <div class="form-group">
                                            <label>Tg chat ID</label>
                                            <input id="tg-chat-id" type="text" name="first_name" class="form-control border-none input-default bg-ash" placeholder="Enter company`s tg chat id" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Thread ID</label>
                                            <input id="thread-id" type="text" name="thread_id" class="form-control border-none input-default bg-ash" placeholder="Enter company`s thread id" required>
                                        </div>
                                        <button id="save-btn-tg" class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="button" data-user-id="">Save</button>
                                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        const user_id = params.get('id');
        const company_id = params.get('company_id');
        var logo = ""
        fetch(`${local_url}/api/admin/company/${company_id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            })
            .then(resp => resp.json())
            .then(data => {
                console.log(data)
                data.users.forEach(e => {
                    if(e.id == user_id){
                        console.log(e)
                        document.getElementById("partner-name").innerText = e.first_name + " " + e.last_name || "none";
                        document.getElementById("partner-location").innerText = e.city;
                        document.getElementById("partner-phone-number").innerText = e.phone_number ? e.phone_number : "none";
                        document.getElementById("partner-address").innerText = e.city ? e.city : "none";
                        document.getElementById("partner-email").innerText = e.email ? e.email : "none";
                        document.getElementById("company-city").value = e.city;
                        document.getElementById("save-btn-tg").dataset.userId = e.id;
                    }
                })
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


                setCompanyLogo(data.company.logo)
            })
            .catch(err => {
                console.log(err)
            })
    })
</script>

<?php include("./components/footer.php"); ?>