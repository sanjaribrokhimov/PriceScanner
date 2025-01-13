<?php include './components/header.php'; ?>

<div id="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="card-header m-b-20">
                        <h4>Edit Tour</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="basic-form">
                                <form id="editTourForm" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="tourTitle">Tour Title</label>
                                        <input type="text" id="tourTitle" class="form-control border-none input-default bg-ash" placeholder="Enter tour title" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tourStatus">Status</label>
                                        <select id="tourStatus" class="form-control border-none input-default bg-ash" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tourDescription">Description</label>
                                        <textarea id="tourDescription" class="form-control border-none input-default bg-ash" placeholder="Enter tour description" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="tourVideoUrl">Video Link</label>
                                        <input type="url" id="tourVideoUrl" class="form-control border-none input-default bg-ash" placeholder="Enter video link">
                                    </div>

                                    <div class="form-group">
                                        <label for="fromCountry">From Country</label>
                                        <input type="text" id="fromCountry" class="form-control border-none input-default bg-ash" placeholder="Enter departure country" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="toCountry">To Country</label>
                                        <input type="text" id="toCountry" class="form-control border-none input-default bg-ash" placeholder="Enter destination country" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tourCategory">Category</label>
                                        <select id="tourCategory" class="form-control border-none input-default bg-ash" required>
                                            <option value="all">all</option>
                                            <option value="family">Family</option>
                                            <option value="beach">Beach</option>
                                            <option value="sunny">Sunny</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Departure Dates</label>
                                        <div id="departuresContainer" class="m-b-20">
                                            <!-- Departure fields will be added here -->
                                        </div>
                                        <button type="button" class="btn btn-success" onclick="addDeparture()">Add Departure</button>
                                    </div>

                                    <div class="form-group image-type">
                                        <label for="tourImages">Upload Images</label>
                                        <input type="file" id="tourImages" name="tourImages[]" accept="image/*" multiple>
                                    </div>

                                    <div id="gallery" class="m-t-20">
                                        <h5>Gallery</h5>
                                        <div class="row" id="imageGallery"></div>
                                    </div>

                                    <button id="updateButton" class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="button" onclick="updateTour()">Update</button>
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

<script>
    const maxSize = 500 * 1024; // 500 KB в байтах

    async function fetchTourData() {
        const urlParams = new URLSearchParams(window.location.search);
        const tourId = urlParams.get('tourId');
        const apiUrl = `${local_url}/api/tour/item/${tourId}`;

        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Error fetching tour data');
            }
            const data = await response.json();
            const tour = data.tour;

            document.getElementById('tourTitle').value = (tour.title || '').replace(/[\{\}"\\]/g, '').trim();
            document.getElementById('tourDescription').value = (tour.description || '').replace(/[\{\}"\\]/g, '').trim();
            document.getElementById('tourVideoUrl').value = (tour.video_url || '').replace(/[\{\}"\\]/g, '').trim();
            document.getElementById('tourStatus').value = (tour.status || 'active').replace(/[\{\}"\\]/g, '').trim(); // Убираем лишние символы и пробелы
            document.getElementById('fromCountry').value = (tour.fromCountry || '').replace(/[\{\}"\\]/g, '').trim(); // Страна отправления
            document.getElementById('toCountry').value = (tour.toCountry || '').replace(/[\{\}"\\]/g, '').trim(); // Страна назначения
            document.getElementById('tourCategory').value = (tour.category || '').replace(/[\{\}"\\]/g, '').trim(); // Категория

            const departuresContainer = document.getElementById('departuresContainer');
            departuresContainer.innerHTML = '';
            tour.departures.forEach(departure => {
                addDeparture(departure.id, departure.departure_date.split('T')[0], departure.price);
            });

            const imageGallery = document.getElementById('imageGallery');
            imageGallery.innerHTML = '';
            tour.images.forEach((image) => {
                const imgElement = document.createElement('img');
                imgElement.src = `${local_url}/api/tour/${image}`; // Adjust if your API endpoint for images differs
                imgElement.alt = 'Tour Image';
                imgElement.className = 'img-fluid col-md-3 col-sm-6 col-xs-12 m-b-20';

                const col = document.createElement('div');
                col.appendChild(imgElement);
                imageGallery.appendChild(col);
            });
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to fetch tour data. Please try again later.');
        }
    }

    async function updateTour() {
        const urlParams = new URLSearchParams(window.location.search);
        const tourId = urlParams.get('tourId');
        const formData = new FormData();
        const token = localStorage.getItem('access_token');

        // Get form data
        formData.append('title', document.getElementById('tourTitle').value);
        formData.append('description', document.getElementById('tourDescription').value);
        formData.append('video_url', document.getElementById('tourVideoUrl').value);
        formData.append('status', document.getElementById('tourStatus').value); // Add status
        formData.append('fromCountry', document.getElementById('fromCountry').value); // Add fromCountry
        formData.append('toCountry', document.getElementById('toCountry').value); // Add toCountry
        formData.append('category', document.getElementById('tourCategory').value); // Add category

        // Gather departure information
        const departureRows = document.getElementById('departuresContainer').getElementsByClassName('row m-b-10');

        for (let row of departureRows) {
            const dateInput = row.getElementsByTagName('input')[0];
            const priceInput = row.getElementsByTagName('input')[1];

            const departureDate = dateInput.value;
            const price = priceInput.value;

            // Make sure both date and price are provided
            if (departureDate && price) {
                const departureObj = {
                    departure_date: departureDate,
                    price: price
                };

                // Appending each departure as a JSON string
                formData.append("departures", JSON.stringify(departureObj));
            }
        }

        // Add uploaded images
        const tourImages = document.getElementById('tourImages').files;
        for (let i = 0; i < tourImages.length; i++) {
            if(tourImages[i].size < maxSize){
                formData.append('images', tourImages[i]);
            }else{
                document.getElementById('tourImages').value = ''; // Сбрасываем выбор файла
                alert("Файл слишком большой! Пожалуйста, выберите файл меньше 500 KB.");
                return;
            }
        }

        try {
            const response = await fetch(`${local_url}/api/tour/item/${tourId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                body: formData,
            });

            if (!response.ok) {
                const errorResponse = await response.json();
                alert(`Error: ${errorResponse.message || 'Unknown error'}`);
                return;
            }

            alert('Tour updated successfully!');
            window.location.href = 'services.php';
        } catch (error) {
            console.error('Error updating tour:', error);
            alert('Failed to update tour. Please try again later.');
        }
    }

    function addDeparture(id, date = '', price = '') {
        const departuresContainer = document.getElementById('departuresContainer');
        const departureRow = document.createElement('div');
        departureRow.className = 'row m-b-10';
        departureRow.innerHTML = `
            <div class="col-md-5">
                <input type="date" class="form-control border-none input-default bg-ash" value="${date}" required>
            </div>
            <div class="col-md-5">
                <input type="number" class="form-control border-none input-default bg-ash" placeholder="Price" value="${price}" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Remove</button>
            </div>
        `;
        departuresContainer.appendChild(departureRow);
    }

    document.addEventListener("DOMContentLoaded", fetchTourData);
</script>

<?php include './components/footer.php'; ?>