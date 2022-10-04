//For daily details edit part
const parent = document.getElementById('parent');
let prevChoice;
parent.addEventListener('change', event => {

    //Adding hide class, and removing required attribute to all model dropdowns, setting all dropdowns to default value
    document.querySelectorAll('.child').forEach(function (elem) {
        elem.classList.add('hide');
        elem.removeAttribute('required');
        elem.selectedIndex = 0;
    })
    
    //Getting the chosen value dropdown
    let userChoice = document.getElementById(`${event.target.value}`);

    //Removing hide class from only chosen dropdown
    userChoice.classList.remove('hide');
    //Adding required attribute to chosen dropdown
    userChoice.setAttribute('required', '');

    userChoice.addEventListener('change', event => {
        let fName = document.getElementById('fName');
        let lName = document.getElementById('lName');

        let arr = `${event.target.value}`.split(":");
        
        fName.value = arr[0];
        fName.classList.add('active');
        
        lName.value = arr[1];
        lName.classList.add('active');
    })
});
