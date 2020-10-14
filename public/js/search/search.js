document.getElementById('_formSearch')
    .onsubmit = (e) => {
        e.preventDefault();
        let search = document.getElementById("_search").value;
        xhrQuery(search);
    };

const url = "/search?q=";

function xhrQuery(search) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url + encodeURIComponent(search));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();
    xhr.onreadystatechange = () => {
        try {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                } else {
                    console.log("Erreur de requete")
                }
            }
        } catch (error) {
            alert("Une exception s’est produite : " + error.description);
        }
    }
};

/**XML: text/xml, application/xml */