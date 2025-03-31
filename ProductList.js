const products = [
    {
        orderId: 1,
        productName: 'Dragon Slayer Sword',
        productImage: './Picture/DragonSlayer.jpg',
        price: 450 ,
        Status: shortage
    },
    {
        orderId: 2,
        productName: 'TNT Bomb',
        productImage: './Picture/TNT.jpeg',
        price: 450 ,
        Status: abundant 
    },
    {
        orderId: 3,
        productName: 'Chiikawa',
        productImage: './Picture/Chiikawa.jpg',
        price: 450 ,
        Status: abundant
    }
];

function populateProductList() {
    const tableBody = document.querySelector('#Product-table tbody');
    tableBody.innerHTML = '';
    products.forEach(product => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${product.orderId}</td>
            <td>${product.productName}</td>
            <td><img src="${products.productImage}" alt="${product.productName}"></td>
            <td>${totalSalesAmount.toFixed(2)}</td>
            <td>${product.Status}</td>
        `;
        tableBody.appendChild(row);
    });
}
document.addEventListener('DOMContentLoaded', populateProductList);
