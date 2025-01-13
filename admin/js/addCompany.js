const openPopupBtn = document.getElementById('company-add-btn');
const closePopupBtn = document.getElementById('close-popup');
const popupWrapper = document.getElementById('popup-wrapper');

openPopupBtn.addEventListener('click', () => {
    popupWrapper.classList.remove('hidden');
});

closePopupBtn.addEventListener('click', () => {
    popupWrapper.classList.add('hidden');
});

popupWrapper.addEventListener('click', (event) => {
    if (event.target === popupWrapper || event.target === document.getElementById('popup-background')) {
        popupWrapper.classList.add('hidden');
    }
});



//==============add company logic=========
// Assuming you have a form with id 'companyForm'
document.getElementById("save-btn").addEventListener('click', async (event) => {
  event.preventDefault();

  const formData = new FormData();
  formData.append('legal_name', document.getElementById('legal_name').value);
  formData.append('name', document.getElementById('name').value);
  formData.append('category', document.getElementById('category').value);
  formData.append('city', document.getElementById('city').value);
  formData.append('district', document.getElementById('district').value);
  formData.append('address', document.getElementById('address').value);

  const logoFile = document.getElementById('logo').files[0];
  if (logoFile) {
    formData.append('logo', logoFile);
  }

  try {
    const response = await fetch(`${local_url}/api/admin/companies/new`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
      },
      body: formData
    });

    const result = await response.json();
    if (response.ok) {
      alert('Company created successfully');
      window.location.href = "./addCompany.php"
    } else {
      alert(`Error: ${result.error}`);
    }
  } catch (error) {
    alert('An error occurred while creating the company.');
  }
});

