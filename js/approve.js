document.querySelectorAll('.bi-arrow-down-square-fill').forEach(elem => {
    elem.addEventListener('click', event => {
        elem.classList.toggle("bi-arrow-down-square-fill");
        elem.classList.toggle("bi-arrow-up-square-fill");
    })
});

function approve(key) {
    var xmlhttp = new XMLHttpRequest();

    let str = key.split("_");
    let email = str[0]+"@"+str[1]+"."+str[2];
    let service = str[3];
    
    str = "approved_"+key;
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("successMsg").innerHTML = this.responseText;
        }
    }
    
    document.getElementById(key).classList.toggle('hide');

    xmlhttp.open("GET", "unapprove.php?key=" + email+":"+service, true);
    xmlhttp.send();
}
