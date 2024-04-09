// Obtaining course data
// If the id is not set, id = -1 in the course.php
if (id != -1) {
    // Call asynchronously the PHP script
    fetch('./../fetch/course_loading.php?id=' + id,{
        // Pass the id with GET
        method: 'GET'
    })
        .then(response => {
            if (!response.ok){
                throw new Error('The network response went wrong');
            }
            // Return a JavaScript object
            return response.json()})
        .then(data => {
            if (data.error) {
                throw new Error('Response data error');
            }
            // Show and visualize course and button
            showCourse(data.course);
            manageButtons(data);
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('Something went wrong');
        });


    fetch('./../fetch/reviews_loading.php?id=' + id + '&&code=1',{
        method: 'GET'
    })
        .then(response => {
            if (!response.ok){
                throw new Error('The network response went wrong');;
            }
            return response.json()})
        .then(data => {
            //console.log(data);
            if(data==null) document.getElementById('l_r_label').innerHTML="There are no reviews";
            else {showOneReview(data);
                document.getElementById('showallreviews').addEventListener('click',obtainallreviews);
            };
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('Something went wrong');
        });
}

/*
Function to show the actual course
 */
function showCourse(data){
    document.getElementById('title').innerHTML = data.name;
    // If data.video == -1 means you don't have the permission to watch the course
    if (data.video == -1) {
        let course_img = document.getElementById('course_img')
        course_img.src = "./../contents/images/course_covers/" + data.image;
        course_img.style.border = 'solid 1px black'
        course_img.alt = data.title;
    }
    // You are allowed
    else {
        let course_img = document.getElementById('course_img');
        let video = document.getElementById('video');

        video.classList.remove('hidden');
        course_img.classList.add('hidden');

        video.src = data.video;
    }
    document.getElementById('course_description').innerHTML = data.description;
    document.getElementById('teacher_info').innerHTML = data.first_name +" "+ data.last_name;
    document.getElementById('price').innerHTML = data.price+'€';

    let stars = document.getElementsByClassName('rating_stars');
    const full = 5;
    // Returns the largest integer less than or equal
    let full_stars = Math.floor(data.rating/2);
    let half_star = data.rating%2;
    let i = 0;
    // Visualize the rating stars
    for (;i<full_stars;i++){
        stars[i].classList.remove('fa-star-o'); // empty star
        stars[i].classList.add('fa-star'); // full star
    }
    if (half_star == 1) {
        stars[i].classList.remove('fa-star-o'); // empty star
        stars[i].classList.add('fa-star-half-o'); // half full star
    }
    document.getElementById('rating_num').innerHTML = "Rating: "+data.rating/2+"/5";
    document.getElementById('difficulty').innerHTML= data.difficulty+"/10";
}

/*
Show the appropriate button
 */
function manageButtons(data) {
    // You are not logged in, so you can't leave a review
    // and, you can't also add it to the cart
    if (!data.login) {
        document.getElementById('addtocart').addEventListener('click', () => {
            window.location.href = "./login.php";
        });
        document.getElementById('course_img').addEventListener('click', () => {
            window.location.href = "./login.php";
        });
        // You are logged in, but you don't have bought the video
        // so, you can't leave a review
    } else if (data.login && data.course.video == -1) {
        document.getElementById('addtocart').addEventListener('click', function() {
            addToCartJS(id); // On click, add to cart function
        });
    // Now you can leave the review
    } else if(data.login && data.course.video !=-1) {
        let button = document.getElementById('addtocart');
        button.innerHTML = "leave a review";
        button.addEventListener("click",()=> {
            document.getElementById('hr_hidden').classList.remove('hidden');
            document.getElementById('fieldset').classList.remove('hidden');

            let position = document.getElementById('fieldset').getBoundingClientRect();
            window.scrollTo(position.x,position.y);

        });
    }
}

/*
Add to cart PHP script called with fetch
 */
function addToCartJS(id){
    // Call asynchronously the script PHP
    fetch('./../fetch/add_to_cart.php?id='+id, {
        // Pass the id with GET
        method: 'GET'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('The network response went wrong');
            }
            return response.json();
        })
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('Something went wrong');
        });
}

/*
Filling the latest review container
 */
function showOneReview(data){
    let stars = document.getElementsByClassName('review_stars');
    let full = 5;
    let full_stars = Math.floor(data.rating/2);
    let half_star = data.rating%2;
    let i = 0;
    // Visualize stars
    for (;i<full_stars;i++){
        stars[i].classList.remove('fa-star-o');
        stars[i].classList.add('fa-star');
    }
    if (half_star == 1) {
        stars[i].classList.remove('fa-star-o');
        stars[i].classList.add('fa-star-half-o');
    }
    document.getElementById('comment').innerHTML=data.comment;
    document.getElementById('author').innerHTML = document.getElementById('author').innerHTML+data.first_name+" "+data.last_name;
    document.getElementById('date').innerHTML= data.date;
}

/*
Function to call the appropriate PHP to obtain all the reviews
 */
function obtainallreviews(){
    // Call asynchronously the PHP script
    fetch('./../fetch/reviews_loading.php?id=' + id + '&&code=0',{
        // Pass the id with GET
        method: 'GET'
    })
        .then(response => {
            if (!response.ok){
                throw new Error('The network response went wrong');
            }
            return response.json()})
        .then(data => {
            showallreviews(data);
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            alert('Something went wrong');
        });
}

/*
Function to show all the reviews
 */
function showallreviews(data){
    let isFirstRow = true;
    document.getElementById('l_r_label').innerHTML = "Latest reviews:";

    data.forEach(row => {
        // Avoid re-showing the first review
        if (isFirstRow) {
            isFirstRow = false;
            return;
        }

        // HTML elements
        let containerDiv = document.createElement('div');
        containerDiv.classList.add('defaultFlex', 'fullwidth');

        let starsDiv = document.createElement('div');
        starsDiv.classList.add('r_stars');

        let tot_stars = 5;
        let full_stars = Math.floor(row.rating/2);
        let half_star = row.rating%2;

        let i = 0
        for (; i < full_stars; i++) {
            let starIcon = document.createElement('i');
            starIcon.classList.add('fa', 'fa-star', 'yellow');
            starsDiv.appendChild(starIcon);
        }
        if (half_star==1){
            let starIcon = document.createElement('i');
            starIcon.classList.add('fa', 'fa-star-half-o', 'yellow');
            starsDiv.appendChild(starIcon);
            i++;
        }
        for (; i<tot_stars;i++){
            let starIcon = document.createElement('i');
            starIcon.classList.add('fa', 'fa-star-o', 'yellow');
            starsDiv.appendChild(starIcon);
        }

        let commentDiv = document.createElement('div');
        commentDiv.classList.add('defaultFlex-column', 'comment');
        let commentLabel = document.createElement('h5');
        commentLabel.classList.add('comment_label');
        commentLabel.textContent = 'Comment:';
        let commentParagraph = document.createElement('p');
        commentParagraph.classList.add('comment_text');
        commentParagraph.textContent = row.comment;
        commentDiv.appendChild(commentLabel);
        commentDiv.appendChild(commentParagraph);

        let flexSpaceDiv = document.createElement('div');
        flexSpaceDiv.classList.add('flex-space-around', 'fullwidth');
        let authorHeading = document.createElement('h5');
        authorHeading.classList.add('author_label');
        authorHeading.textContent = 'Author:'+row.first_name+" "+row.last_name;
        let dateHeading = document.createElement('h5');
        dateHeading.classList.add('date_label');
        dateHeading.textContent = row.date;
        flexSpaceDiv.appendChild(authorHeading);
        flexSpaceDiv.appendChild(dateHeading);

        let hrElement = document.createElement('hr');
        hrElement.classList.add('review_line');

        // Adding HTML elements to the containers
        containerDiv.appendChild(starsDiv);
        containerDiv.appendChild(commentDiv);

        document.getElementById('reviews_div').appendChild(containerDiv);
        document.getElementById('reviews_div').appendChild(flexSpaceDiv);
        document.getElementById('reviews_div').appendChild(hrElement);
    });

    let button = document.getElementById('showallreviews');
    button.disabled = true;
    button.removeEventListener('click',obtainallreviews);
}
