// Call asynchronously the script cart_loading.php
fetch('./../fetch/cart_loading.php')
    .then(response => {
        if (!response.ok){
            throw new Error('The network response went wrong');;
        }
        // return a JavScript object
        return response.json()})
    .then(data => {
        if (data.error) alert('Something went wrong');
        // If there are no errors
        else {
            showCart(data);
            let button = document.getElementById('buy');
            if (data.rows>0){
                // Add event listener, on click use the buy cart function
                button.addEventListener('click', buyCart);
            }
            else {
                button.addEventListener('click',()=>{
                    alert('You have nothing in your cart to buy');});
            }
            button.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error during fetch:', error);
        alert('Something went wrong');
    });

/*
Function to show the courses in the cart
 */
function showCart(data){
    let price = 0.00;
    if (data.rows>1){
        document.getElementById('default_course_div').classList.add('hidden');
        // For every course call the function to show it in the cart
        // "For each" likes an array
        data.datas.forEach(row => {
            CreateCourseInfos(row);
            // Increment the total price
            price = price + Number(row.price);
        });
        // For just a course
    }else if (data.rows == 1){
        document.getElementById('default_course_div').classList.add('hidden');
        CreateCourseInfos(data.datas);
        // Do not increment the price obviously
        price = Number(data.datas.price);
    }
    document.getElementById('total').innerHTML="Total: "+ price.toFixed(2) +"€";
}

/*
Function for the buy cart button implementation
 */
function buyCart(){
    let price = document.getElementById('total').innerHTML;
    let ok = confirm('Do you really want to buy all the courses in the cart? You will spend:'+price+'\n (the payment method is not yet implemented, with ok the purchase will be simulated)');
    if(ok){
        // Call asynchronously the script buy_cart.php
        fetch('./../fetch/buy_cart.php')
            .then(response => {
                if (!response.ok){
                    throw new Error('The network response went wrong');;
                }
                // Return a JavaScript object
                return response.json()})
            .then(data => {
                // Data is a simple code for success or errors
                if (data < 0) alert('Something went wrong');
                else {
                    alert('Successfully purchased!');
                    // Reload to see all changes
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
                alert('Something went wrong');
            });
    }
}

/*
Function to remove a course from the cart
 */
function removeCourse(id){
    // Call asynchronously the script buy_cart.php
    fetch('./../fetch/remove_from_cart.php?id='+id,{
        // Pass with GET the course id
        method: "GET"
    })
        .then(response => {
            if (!response.ok){
                alert('something went wrong');
            }
            // Return a JavaScript object
            return response.json()})
        .then(data => {
            // Data is just a code
            if (data == 0) location.reload();
            else alert("Someting went wrong!");

        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('Something went wrong');
        });
}