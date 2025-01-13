
let currentPage = 1;
const perPage = 10;

function fetchCompanies(page = 1) {
        fetch(`${local_url}/api/admin/companies?page=${page}&per_page=${perPage}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem("access_token")}` 
            }
        })
        .then(response => response.json())
        .then(response => {
            if (response.message) {
                const companiesList = response.companies;
                const totalPages = response.pages;
                const currentPage = response.current_page;

                let companiesHtml = '';
                companiesList.forEach(company => {
                    companiesHtml += `
                        <tr>
                            <td>${company.name}</td>
                            <td>${company.name || 'N/A'}</td> <!-- Add category -->
                            <td class="color-primary" style="text-transform:uppercase;">${company.category}</td> <!-- Info can be anything -->
                            <td>
                                <button class="btn btn-primary" onclick="window.location.href='companyInfo.php?company_id=${company.id}'">Info</button>
                            </td>
                        </tr>
                    `;
                });
                // <button class="btn btn-secondary" onclick="connectCompany(${company.id})">Connect</button> -
                document.querySelector('tbody').innerHTML = companiesHtml;

                let paginationHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    paginationHtml += `
                        <button onclick="fetchCompanies(${i})" class="${i === currentPage ? 'active' : ''}">
                            ${i}
                        </button>
                    `;
                }
                document.getElementById('pagination').innerHTML = paginationHtml;
            } else {
                document.querySelector('tbody').innerHTML = `<tr><td colspan="4">No companies found.</td></tr>`;
            }
        })
        .catch(error => {
            console.error('Error fetching companies:', error);
            document.getElementById('companies-container').innerHTML = `<p>Error loading companies.</p>`;
        });
}
window.onload = function() {
    fetchCompanies(currentPage);  
}