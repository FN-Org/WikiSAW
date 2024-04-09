// Avoid an empty input
if (request != ""){
    // Call asynchronously the PHP script
    fetch('./../fetch/search_script.php?query=' + request, {
        // Pass the user inputs as a GET request
        method: 'GET'
    }).then(response => {
        if (!response.ok) {
            throw new Error('The network response went wrong');;
        } return response.json();
    }).then(data => {
        if (data.error) {
            alert('Something went wrong');
        } else show_results(data);
    })
} else{
    document.getElementById('results').innerHTML= "Please search something";
}

/*
Function to show the results
 */
function show_results(data){
    document.getElementById('results').innerHTML= "Results for: "+request;
    data.courses.forEach(row => {
        CreateCourseInfos(row);
    })
}