const products = [
    {
        productId: 1,
        productName: 'Dragon Slayer Sword',
        productImage: './Picture/DragonSlayer.jpg',
        price: 450 ,
        Status: 'shortage' 

    },
    {
        productId: 2,
        productName: 'TNT Bomb',
        productImage: './Picture/TNT.jpeg',
        price: 150  ,
        Status: 'shortage' 
    },
    {
        productId: 3,
        productName: 'Chiikawa',
        productImage: './Picture/Chiikawa.jpg',
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
            <td><img src="${product.productImage}" alt="${product.productName}"></td>
            <td>${product.price}</td>
            <td>${product.Status}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Initial population of the report table
document.addEventListener('DOMContentLoaded', populateReportTable);
