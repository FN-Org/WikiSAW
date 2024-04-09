// Menage responsive navigation bar and navigation button
let isSearchBarVisible = false;
if (window.innerWidth < 800) {
    document.getElementById('searchButton').disabled = true;
    document.getElementById('searchIcon').addEventListener("click", toggleSearchBar);
} else {
    document.getElementById('searchIcon').addEventListener("mouseover", displayBar);
    document.getElementById('searchContainer').addEventListener("mouseout", removeBar);
}

// Function to display the search bar
function displayBar() {

    const searchInput = document.getElementById('searchBar');
    const searchContainer = document.getElementById('searchContainer');

    document.getElementById('searchContainer').addEventListener("mouseover", displayBar);

    searchInput.style.display = 'inline';

    searchContainer.style.border = 'solid 1px black';
    searchContainer.style.borderRadius = '2rem';
}

// Function to hide the search bar
function removeBar() {
    const searchInput = document.getElementById('searchBar');
    const searchContainer = document.getElementById('searchContainer');

    searchInput.style.display = 'none';

    searchContainer.style.border = 'none';
    searchContainer.style.borderRadius = 'none';
}

let count = 1;
const menuButton = document.getElementById('dropButton');

menuButton.addEventListener("click", function() {
    count = toggleMenu(count);
});

function toggleMenu(variable) {
    if (variable === 1) {
        document.getElementById('dropdownContent').style.display = 'inline';
        return 2;
    } else if (variable === 2) {
        document.getElementById('dropdownContent').style.display = 'none';
        return 1;
    }
}

function toggleSearchBar() {
    isSearchBarVisible = !isSearchBarVisible;

    const searchContainer = document.getElementById('searchContainer');
    const searchInput = document.getElementById('searchBar');
    const searchButton = document.getElementById('searchButton');

    if (isSearchBarVisible) {
        // Mostra la barra di ricerca
        searchInput.style.display = 'inline';
        searchContainer.style.border = 'solid 1px black';
        searchContainer.style.borderRadius = '2rem';
        searchContainer.style.backgroundColor = 'white';
        searchContainer.style.position = 'absolute';
        searchContainer.style.top = '4.3rem';
        setTimeout(function() {
            document.getElementById('searchButton').disabled = false;
        }, 1);
    } else {
        // Nascondi la barra di ricerca
        searchInput.style.display = 'none';
        searchContainer.style.border = 'none';
        searchContainer.style.borderRadius = 'none';
        searchContainer.style.backgroundColor = 'transparent';
        searchContainer.style.position = 'static';
        searchContainer.style.top = '0rem';
        if (searchInput.value === '') {
            searchButton.disabled = true;
        }
    }
}

window.addEventListener("scroll", navDisappear);

let lastScrollTop = 0;

function navDisappear() {
    const navbar = document.querySelector('nav');

    let currentScrollTop = window.scrollY;

    if (currentScrollTop > lastScrollTop) {
        // Scroll verso il basso
        navbar.style.display = 'none';
    } else if (currentScrollTop <= lastScrollTop) {
        // Scroll verso l'alto o fermo
        navbar.style.display = '';
    }

    lastScrollTop = currentScrollTop;
}