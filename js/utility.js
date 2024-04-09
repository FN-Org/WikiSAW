// Function to visualize errors in registration.php
function error(stringa) {
    if (document.getElementsByClassName('error').length > 0) {
        const ok = document.querySelector('.error');
        ok.textContent = stringa;
    }
    else {
        const errorMessage = document.createElement('h6');
        errorMessage.classList.add('error');
        errorMessage.textContent = stringa;
        const el = document.getElementById('formContainer');
        el.insertBefore(errorMessage, document.querySelector('h2').nextSibling);
    }
}

/*
Function to create HTML elements to visualize all the info of a course in the cart page
 */
function CreateCourseInfos(row){
    // div element (class course)
    let divCourse = document.createElement('div');
    divCourse.classList.add('course')

    // <a> element
    let linkElement = document.createElement('a');
    linkElement.href = './course.php?id='+row.id;

    // <img> element
    let imageElement = document.createElement('img');
    imageElement.classList.add('courseImage');
    imageElement.src = './../contents/images/course_covers/'+row.image;
    imageElement.alt = row.name;

    // <div> element for course info
    let courseInfoDiv = document.createElement('div');
    courseInfoDiv.classList.add('courseInfo');

    // <h4> element for the title
    let titleElement = document.createElement('h4');
    titleElement.classList.add('courseTitle');
    titleElement.textContent = row.name;

    // <p> element for the course description
    let descriptionElement = document.createElement('p');
    descriptionElement.classList.add('courseDescription');
    descriptionElement.textContent = row.description;

    // <div> element for the course rating
    let ratingDiv = document.createElement('div');
    ratingDiv.classList.add('rating');
    let tot_stars = 5;
    // Returns the largest integer less than or equal
    let full_stars = Math.floor(row.rating/2);
    let half_star = row.rating%2;

    // Show all the stars for the rating
    let i = 0;
    for (; i < full_stars; i++) {
        let starIcon = document.createElement('i');
        starIcon.classList.add('fa', 'fa-star', 'yellow');
        ratingDiv.appendChild(starIcon);
    }
    if (half_star == 1){
        let starIcon = document.createElement('i');
        starIcon.classList.add('fa', 'fa-star-half-o', 'yellow');
        ratingDiv.appendChild(starIcon);
        i++;
    }
    for (; i < tot_stars; i++){
        let starIcon = document.createElement('i');
        starIcon.classList.add('fa', 'fa-star-o', 'yellow');
        ratingDiv.appendChild(starIcon);
    }

    // <span> element for the price
    let priceSpan = document.createElement('span');
    priceSpan.classList.add('coursePrice');
    priceSpan.textContent = row.price+'€';

    // Remove button
    let removeButton = document.createElement('button');
    removeButton.classList.add('cartButton','removebutton','backColor2');
    removeButton.innerHTML="Remove";
    // Add an event, on click use the remove course function
    removeButton.addEventListener('click',() => {
        removeCourse(row.id);
    });

    // Adding child elements to the parents
    courseInfoDiv.appendChild(titleElement);
    courseInfoDiv.appendChild(descriptionElement);
    courseInfoDiv.appendChild(ratingDiv);
    courseInfoDiv.appendChild(priceSpan);
    courseInfoDiv.appendChild(removeButton);

    linkElement.appendChild(imageElement);

    divCourse.appendChild(linkElement);
    divCourse.appendChild(courseInfoDiv);

    // Adding the created element inside the div container
    let div_courses = document.getElementById('courses');
    div_courses.appendChild(divCourse);
}