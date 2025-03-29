const orders = [
    {
        orderId: 1,
        orderDate: '2023-03-01',
        productId: 'P001',
        orderQuantity: 2,
        totalAmount: 200,
        customerName: 'John Doe',
        orderStatus: 'Pending',
        materials: [
            { name: 'Material A', physicalQuantity: 100, reservedQuantity: 20, unit: 'kg' }
        ]
    },
    {
        orderId: 2,
        orderDate: '2023-03-02',
        productId: 'P002',
        orderQuantity: 1,
        totalAmount: 100,
        customerName: 'Jane Smith',
        orderStatus: 'Pending',
        materials: [
            { name: 'Material B', physicalQuantity: 50, reservedQuantity: 10, unit: 'kg' }
        ]
    }
];

function populateOrdersTable() {
    const tableBody = document.querySelector('#orders-table tbody');
    tableBody.innerHTML = '';
    orders.forEach(order => {
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${order.orderId}</td>
            <td>${order.orderDate}</td>
            <td>${order.productId}</td>
            <td>${order.orderQuantity}</td>
            <td>${order.totalAmount}</td>
            <td>${order.customerName}</td>
            <td>${order.orderStatus}</td>
            <td>
                <button onclick="updateOrder(${order.orderId})">Update</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function updateOrder(orderId) {
    const order = orders.find(o => o.orderId === orderId);
    if (order) {
        const newQuantity = prompt("Enter new order quantity:", order.orderQuantity);
        const newStatus = prompt("Enter new order status (accepted/rejected):", order.orderStatus);
        
        if (newQuantity !== null) {
            order.orderQuantity = parseInt(newQuantity);
            order.totalAmount = order.orderQuantity * 100; 
        }

        if (newStatus !== null && (newStatus === 'accepted' || newStatus === 'rejected')) {
            order.orderStatus = newStatus;
        }
        order.materials.forEach(material => {
            material.reservedQuantity += order.orderQuantity; 
        });

        document.getElementById('message').innerText = "Order updated successfully!";
        populateOrdersTable();
    }
}

document.addEventListener('DOMContentLoaded', populateOrdersTable);
