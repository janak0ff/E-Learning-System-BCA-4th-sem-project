// ------------ scroll function  ------------
window.onscroll = (e) => {
    // ------------ scroll indicator ------------
    let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrolled = (winScroll / height) * 100;
    document.getElementById("myBar").style.width = scrolled + "%";

    // ------------- scroll up btn show --------------
    (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) ? document.getElementById("scrollup").style.display = "block" : document.getElementById("scrollup").style.display = "none";

    //------------- hide navbar on down scroll and show on up scroll -----------------
    if (prevScrollpos > window.pageYOffset) {
        document.getElementById("main-header").style.display = "block";
        // document.getElementById("info0ff").style.display = "none";
    } else {
        document.getElementById("main-header").style.display = "none";
        // document.getElementById("info0ff").style.display = "block";
    }
    prevScrollpos = window.pageYOffset;
}
let prevScrollpos = window.pageYOffset;
// ---------- scroll function end --------------


// <!-- ------------------- pop up windows feedback box -------------- -->
document.addEventListener('contextmenu', function (event) {
    event.preventDefault(); // Prevent the default context menu from showing

    const contextMenu = document.querySelector(".window-containerpop");
    contextMenu.style.display = 'block'; // Show the context menu
    contextMenu.style.left = event.pageX + 'px'; // Set the x-coordinate
    contextMenu.style.top = event.pageY + 'px'; // Set the y-coordinate

    // Add a click event listener to the document to hide the context menu when the user clicks outside of it
    document.addEventListener('click', function hideContextMenu(event) {
        if (!contextMenu.contains(event.target)) {
            contextMenu.style.display = 'none'; // Hide the context menu
            document.removeEventListener('click', hideContextMenu); // Remove the event listener
        }
    });
});
//   ----------------- end -------------------------

// for expand and collapse
const items = document.querySelectorAll('.dictionary button');

function toggledictionary() {
    const itemToggle = this.getAttribute('aria-expanded');

    for (i = 0; i < items.length; i++) {
        items[i].setAttribute('aria-expanded', 'false');
    }

    if (itemToggle == 'false') {
        this.setAttribute('aria-expanded', 'true');
    }
}

items.forEach(item => item.addEventListener('click', toggledictionary));




// for search 
const searchBar = document.getElementById('search-bar');
searchBar.addEventListener('input', searchDictionary);

function searchDictionary() {
    const query = searchBar.value.toLowerCase();
    const dictionaryItems = document.querySelectorAll('.dictionary-item');

    dictionaryItems.forEach(item => {
        const title = item.querySelector('.title').textContent.toLowerCase();

        if (title.includes(query)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// function searchDictionary() {
//     const query = searchBar.value.toLowerCase();
//     const dictionaryItems = document.querySelectorAll('.dictionary-item');

//     if (query.length > 2) {
//         // Fetch search results from the database
//         const xhr = new XMLHttpRequest();
//         xhr.open('GET', '../search.php?q=' + query);
//         xhr.onload = function() {
//             if (xhr.status === 200) {
//                 const results = JSON.parse(xhr.responseText);

//                 // Display the search results
//                 dictionaryItems.forEach(item => {
//                     const id = item.getAttribute('id').substr(6);
//                     const title = item.querySelector('.title').textContent.toLowerCase();

//                     if (title.includes(query) || results.includes(id)) {
//                         item.style.display = 'block';
//                     } else {
//                         item.style.display = 'none';
//                     }
//                 });
//             }
//         };
//         xhr.send();
//     } else {
//         // Display all items if the query is too short
//         dictionaryItems.forEach(item => {
//             item.style.display = 'block';
//         });
//     }
// }




/* When the user clicks on the profile icon, toggle between hiding and showing the dropdown content */
function openDropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// account delete conform box 

function confirmDelete() {
    var dialogBox = document.createElement('div');
    dialogBox.className = 'alertcenter';

    var message = document.createElement('p');
    message.textContent = 'Are you sure you want to permanently delete your account? This action cannot be undone.';

    var confirmBtn = document.createElement('button');
    confirmBtn.textContent = 'Delete';
    confirmBtn.onclick = function () {
        window.location = '../delete-user.php';
    };

    var cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Cancel';
    cancelBtn.onclick = function () {
        document.body.removeChild(dialogBox);
    };

    dialogBox.appendChild(message);
    dialogBox.appendChild(confirmBtn);
    dialogBox.appendChild(cancelBtn);

    document.body.appendChild(dialogBox);
}


// ajax for views, comments and like functionality 
function incrementViews(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Update the views count in the DOM
            document.getElementById("title-" + id).querySelector(".janakviews").innerHTML = "Views: " + this.responseText;
        }
    };
    xhttp.open("GET", "views.php?id=" + id, true);
    xhttp.send();
}


function incrementLikes(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Update the likes count in the DOM
            document.getElementById("title-" + id).querySelector(".janaklikes").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(32, 9, 216, 1);transform: ;msFilter:;"><path d="M4 21h1V8H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2zM20 8h-7l1.122-3.368A2 2 0 0 0 12.225 2H12L7 7.438V21h11l3.912-8.596L22 12v-2a2 2 0 0 0-2-2z"></path></svg> ' + this.responseText;
        }
    };
    xhttp.open("GET", "likes.php?id=" + id, true);
    xhttp.send();
}

function submitComment(id) {
    // Get the form data
    var titleid = document.getElementById('titleid-' + id).value;
    var namec = document.getElementById('namec-' + id).value;
    var titlec = document.getElementById('titlec-' + id).value;
    var descriptionc = document.getElementById('descriptionc-' + id).value;

    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open('POST', 'comments.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Define the callback function
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
        }
    };

    // Prepare the request data
    var data = 'titleid=' + encodeURIComponent(titleid) +
        '&namec=' + encodeURIComponent(namec) +
        '&titlec=' + encodeURIComponent(titlec) +
        '&descriptionc=' + encodeURIComponent(descriptionc);

    // Send the request
    xhr.send(data);
}


// end /
