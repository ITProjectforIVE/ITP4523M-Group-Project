const orders = [
    {
        orderId: 1,
        productName: 'Dragon Slayer Sword',
        productImage: './Picture/DragonSlayer.jpg',
        orderQuantity: 2,
        price: 100 // Price per item
    },
    {
        orderId: 2,
        productName: 'TNT Bomb',
        productImage: './Picture/TNT.jpeg',
        orderQuantity: 1,
        price: 150 // Price per item
    },
    {
        orderId: 3,
        productName: 'Product C',
        productImage: 'https://via.placeholder.com/50',
        orderQuantity: 5,
        price: 200 // Price per item
    }
];

function populateReportTable() {
    const tableBody = document.querySelector('#report-table tbody');
    tableBody.innerHTML = '';
    orders.forEach(order => {
        const totalSalesAmount = order.orderQuantity * order.price;
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${order.orderId}</td>
            <td>${order.productName}</td>
            <td><img src="${order.productImage}" alt="${order.productName}"></td>
            <td>${order.orderQuantity}</td>
            <td>${totalSalesAmount.toFixed(2)}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Initial population of the report table
document.addEventListener('DOMContentLoaded', populateReportTable);