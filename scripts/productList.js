const products = [
    {
        productId: 1,
        productName: 'Dragon Slayer Sword',
        price: 450 ,
        Status: 'shortage' 

    },
    {
        productId: 2,
        productName: 'TNT Bomb',
        price: 150  ,
        Status: 'shortage' 
    },
    {
        productId: 3,
        productName: 'Chiikawa',
        price: 200 ,
        Status: 'sufficient' 
    }
];

function populateReportTable() {
    const tableBody = document.querySelector('#product-table tbody');
    tableBody.innerHTML = '';
    products.forEach(product => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${product.productId}</td>
            <td>${product.productName}</td>
            <td>${product.price}</td>
            <td>${product.Status}</td>
        `;
        tableBody.appendChild(row);
    });
}


document.addEventListener('DOMContentLoaded', populateReportTable);
