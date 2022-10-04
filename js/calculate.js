// Get elements from html
var radioBtns = document.getElementsByName('time');
var dayDropdown = document.getElementById('day');
// Variables for calculating the total price
var total = 0;              //stores total price
var pricePerHour = 8;       //holds price per hour
var days = 0;               //stores how many days user selected per week
var isFullTime;             //stores if user selected full or part time
// Controllers for calculation
var radioRdy = false;
var selectRdy = false;

//Add event listener to both buttons
for (let i = 0; i < radioBtns.length; i++) {
    radioBtns[i].addEventListener('change', event => {
        if (radioBtns[i].value == 'fullTime') {
            isFullTime = true;
        } else {
            isFullTime = false;
        }
        radioRdy = true;
    })
}

// Add event listener to dropdown
dayDropdown.addEventListener('change', event => {
    //get the intended day value
    days = `${event.target.value}`;
    selectRdy = true;
})

// Control for calculation after changes on radio and dropdown selection 
$('select').on('change', event => {
    if (radioRdy && selectRdy)
        calculate(isFullTime, days);
});
$('form input:radio').on('change', event => {
    if (radioRdy && selectRdy)
        calculate(isFullTime, days);
})


// Calculate & print total
function calculate(isFullTime, days) {
    let txt = document.getElementById('total');
    if (isFullTime) {
        total = (days) * (8) * (pricePerHour);
    } else {
        total = (days) * (5) * (pricePerHour);
    }
    txt.innerHTML = "€" + total;
}

