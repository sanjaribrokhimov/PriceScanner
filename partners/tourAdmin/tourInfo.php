<?php include './components/header.php'; ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-body">
                    <div class="user-profile">
                        <div class="user-send-message">
                            <button class="btn btn-primary btn-addon" type="button" onclick="editTour()">
                                <i class="ti-pencil"></i>Edit
                            </button>
                        </div>
                        <br><br>
                        <div class="col-lg-12">
                            <div class="user-photo m-b-30">
                                <img class="img-fluid main-image" src="assets/images/user-profile.jpg" alt="" id="tourImage" />
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="user-profile-name" id="tourTitle">Tour Title</div>
                            <div class="contact-information">
                                <div class="phone-content">
                                    <span class="contact-title"><h3>Description:</h3></span>
                                    <br>
                                    <span id="tourDescription"></span>
                                </div>
                                <div class="phone-content">
                                    <span class="contact-title"><h3>Status:</h3></span>
                                    <span id="tourStatus"></span>
                                </div>
                                <div style="color: black;" class="website-content">
                                    <span class="contact-title"><h3>From Country:</h3></span>
                                    <span id="tourFromCountry"></span>
                                </div>
                                <div style="color: black;" class="website-content">
                                    <span class="contact-title"><h3>To Country:</h3></span>
                                    <span id="tourToCountry"></span>
                                </div>
                                <div style="color: black;" class="website-content">
                                    <span class="contact-title"><h3>Category:</h3></span>
                                    <span id="tourCategory"></span>
                                </div>
                                <br>
                                <div class="email-content">
                                    <span class="contact-title"><h3>Video:</h3></span>
                                    <div id="tourVideoContainer">
                                        <span id="tourVideoUrl"></span>
                                    </div>
                                </div>
                                <div class="phone-content">
                                    <span class="contact-title"><h3>Departures:</h3></span>
                                    <ul id="tourDepartures" class="no-departures"></ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Gallery -->
                    <div class="gallery">
                        <h3>Gallery</h3>
                        <div id="tourGallery" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="search" style="display: none;">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<style>
    .main-image {
        width: 100%;
        height: auto;
        max-height: 400px; /* Set maximum height for main image */
        object-fit: cover; /* Ensure the image maintains proportions */
        border-radius: 8px; /* Rounded corners for aesthetics */
    }
    .gallery img {
        width: 100%;
        height: auto;
        border-radius: 8px; /* Rounded corners for images in gallery */
        margin-bottom: 15px; /* Margin between images */
    }
</style>

<script>
    // Function to fetch data from API and display it on the page
    async function fetchTourInfo() {
        const urlParams = new URLSearchParams(window.location.search);
        const tourId = urlParams.get('tourId'); // Tour ID from URL
        const apiUrl = `${local_url}/api/tour/item/${tourId}`;

        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Error fetching data from API');
            }
            const data = await response.json();
            const tour = data.tour;
            console.log(tour);
            // Fill in data on the page
            document.getElementById('tourTitle').innerText = tour.title || 'N/A';
            document.getElementById('tourDescription').innerText = tour.description || 'N/A';
            document.getElementById('tourStatus').innerText = tour.status || 'N/A';
            document.getElementById('tourFromCountry').innerText = tour.fromCountry || 'N/A';
            document.getElementById('tourToCountry').innerText = tour.toCountry || 'N/A';
            document.getElementById('tourCategory').innerText = tour.category || 'N/A';

            // Display main image of the tour
            if (tour.images && tour.images.length > 0) {
                document.getElementById('tourImage').src = `${local_url}/api/tour/${tour.images[0]}`;
            }

            // Display all other images of the tour
            const gallery = document.getElementById('tourGallery');
            gallery.innerHTML = ''; // Clear gallery
            if (tour.images && tour.images.length > 1) {
                tour.images.slice(1).forEach(image => {
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-lg-3 col-md-4 col-sm-6 mb-2';
                    const imgElement = document.createElement('img');
                    imgElement.src = `${local_url}/api/tour/${image}`;
                    imgElement.alt = 'Tour Image';
                    colDiv.appendChild(imgElement);
                    gallery.appendChild(colDiv);
                });
            } else {
                gallery.innerHTML = '<p>No images available.</p>';
            }

            // Embed YouTube video if video URL is provided
            const videoContainer = document.getElementById('tourVideoContainer');
            if (tour.video_url) {
                const videoId = extractYouTubeId(tour.video_url);
                if (videoId) {
                    videoContainer.innerHTML = `<iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                } else {
                    document.getElementById('tourVideoUrl').innerText = tour.video_url;
                }
            } else {
                document.getElementById('tourVideoUrl').innerText = 'No video available';
            }

            const departuresList = document.getElementById('tourDepartures');
            departuresList.innerHTML = ''; // Clear list
            if (tour.departures && tour.departures.length > 0) {
                tour.departures.forEach(departure => {
                    const listItem = document.createElement('li');
                    const departureDate = departure.departure_date.split('T')[0]; // Get only the date
                    listItem.textContent = `Date: ${departureDate}, Price: ${departure.price}`;
                    departuresList.appendChild(listItem);
                });
            } else {
                departuresList.innerHTML = '<li>No available departures.</li>';
            }

        } catch (error) {
            console.error('Error:', error);
        }
    }

// Helper function to extract YouTube video ID
function extractYouTubeId(url) {
    // Remove any braces or special characters
    url = url.replace(/[\{\}]/g, '').trim(); 

    // Regular expression to match YouTube video ID (for standard and live URLs)
    const regex = /(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regex);
    return match ? match[1] : null;
}


    // Call function on page load
    window.onload = fetchTourInfo;

    function editTour() {
        // Delay before redirecting to editTour.php
        setTimeout(() => {
            // Redirect to editTour.php
            const urlParams = new URLSearchParams(window.location.search);
            const tourId = urlParams.get('tourId'); // Get the tourId from the URL
            window.location.href = `editTour.php?tourId=${tourId}`;
        }, 1000); // Delay in milliseconds (1000 ms = 1 second)
    }
</script>

<?php include './components/footer.php'; ?>
