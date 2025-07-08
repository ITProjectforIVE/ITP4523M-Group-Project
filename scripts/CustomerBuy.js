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


document.getElementById('order-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const order = {
        OrderId: Math.floor(Math.random() * 1000), 
        customerId: Math.floor(Math.random() * 1000),
        orderDate: new Date().toLocaleDateString(),
        productId: "P001",
        orderQuantity: 1,
        orderCost: 100,
        deliveryDate: new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString(),
        orderStatus: "Pending"
    };
    alert('You Successfully make a order!');
    viewOrders();
  });


function viewOrders() {
    const orderRecordsDiv = document.getElementById('order-status');
    orderRecordsDiv.innerHTML = `
        <div>
            <p>Your Order ID: ${Math.floor(Math.random() * 1000)}</p>
            <p>Order Date: ${new Date().toLocaleDateString()}</p>
            <p>Product ID: 1</p>
            <p>Order Quantity: 1</p>
            <p>Order Cost: ${products.product.price}</p>
            <p>Customer ID: ${Math.floor(Math.random() * 1000)}</p>
            <p>Order Delivery Date: ${new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString()}</p>
            <p>Order Status: Pending</p>
            <button onclick="deleteOrder()">Delete Order</button>
        </div>
    `;
  }




function deleteOrder(){
    let warning = "Do you want to cancel the order?";
    if(confirm(warning) == true){
      alert("You have successfully cancel the order! ");
    }
    else{
      return false;
    }
  }