function getData(str) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("list").innerHTML = this.responseText;
        }
    }

    xmlhttp.open("GET", "listByName.php?var=" + email + ":" + str + ":" + access, true);
    xmlhttp.send();
}

function getDate(date) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("list").innerHTML = this.responseText;
        }
    }

    xmlhttp.open("GET", "listByDate.php?var=" + email + ":" + date + ":" + access, true);
    xmlhttp.send();
}