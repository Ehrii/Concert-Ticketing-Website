

items.forEach(item => {
    item.addEventListener("click", () => {
        item.classList.toggle("checked");

        let checked = document.querySelectorAll(".checked"),
            btnText = document.querySelector(".btn-text");

        if (checked.length > 5) {
            item.classList.remove("checked");
        }

        checked = document.querySelectorAll(".checked");

        if (checked && checked.length > 0) {
            btnText.innerText = `${checked.length} Selected Seats`;
        } else {
            btnText.innerText = "Select Seats";
        }
    });
});


const checkboxes = document.querySelectorAll('.container .checkbox');
const chosenSeatsDiv = document.getElementById('chosenSeats');
let selectedCheckboxes = 0;


checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            selectedCheckboxes++;
        } else {
            selectedCheckboxes--;
        }

        if (selectedCheckboxes > 5) {
            checkbox.checked = false;
            selectedCheckboxes--;
        }
    });
});

function resetCheckboxes() {
    const checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    selectedCheckboxes = 0;

    const label = document.getElementById('labeltext');
    if (label) {
        label.textContent = 'Chosen Seats: ';
    }

    selectedCheckboxes = 0;


}

function resetRadio() {
    var radioButtons = document.querySelectorAll('input[name="card"]');
    radioButtons.forEach(function (radioButton) {
        radioButton.checked = false;

    });
    changeImage('css/images/concertmap.png', 'Choose a seat zone to view');
}

function redirectToPage(destination) {
    window.location.href = destination;
}





// function Cash() {
//     // document.getElementById('paymentconfirmcash').style.display = 'block';
//     // document.getElementById('pininput').classList.add('hidden');
//     // var elementsToHide = ['paymentconfirm', 'cardlink', 'pintext', 'note'];
//     // for (var i = 0; i < elementsToHide.length; i++) {
//     //     var element = document.getElementById(elementsToHide[i]);
//     //     if (element) {
//     //         element.style.display = 'none';
//     //     }
//     // }

// }


// function CreditCard() {
//     // document.getElementById('paymentconfirmcash').style.display = 'none';
//     // document.getElementById('pininput').classList.remove('hidden');

//     // var elementsToShow = ['paymentconfirm', 'pinconfirm', 'cardlink', 'pintext', 'note'];
//     // for (var i = 0; i < elementsToShow.length; i++) {
//     //     var element = document.getElementById(elementsToShow[i]);
//     //     if (element) {
//     //         element.style.display = 'block';

//     //         // Inside the loop, check if the current element is the pin input and set its width to 100%
//     //         if (elementsToShow[i] === 'pininput') {
//     //             element.style.width = '100%';
//     //         }
//     //     }
//     // }

// }


function handlePaymentSelection(paymentMode) {
    document.getElementById('paymentmode').textContent = "Payment Mode: " + paymentMode;

    if (paymentMode === 'Cash') {
        window.location.href = 'cash.php';

    } else if (paymentMode === 'Credit Card') {
        window.location.href = 'creditcard.php';
    }


}




document.addEventListener('DOMContentLoaded', function () {
    const liveSearchInput = document.getElementById('liveSearchInput');
    // const radioInput = document.getElementById('radio');

    const listItems = document.querySelectorAll('.list-items');

    liveSearchInput.addEventListener('input', function () {
        const searchQuery = liveSearchInput.value.toLowerCase().trim();

        filterResults(searchQuery);
    });

    function filterResults(searchQuery) {
        listItems.forEach(function (list) {
            const items = list.querySelectorAll('.item');
            items.forEach(function (item) {
                const itemText = item.querySelector('.item-text').textContent.toLowerCase();

                if (itemText.includes(searchQuery)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            const noResults = Array.from(items).every(item => item.style.display === 'none');
            if (noResults) {
                list.style.display = 'none';
            } else {
                list.style.display = 'block';
            }
        });
    }
});

function changeImage(imageSrc, seatZonePrice, redirectUrl) {
    var imgElement = document.getElementById('seatimage');
    imgElement.src = imageSrc;
    var priceLabel = document.getElementById('seatprice');
    priceLabel.textContent = "Seat Zone Price: " + seatZonePrice
    if (redirectUrl) {
        window.location.href = redirectUrl;
    }

}

document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.checkbox');
    const chosenSeatsLabel = document.getElementById('labeltext');
    const totalAmountSpan = document.getElementById('result');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateChosenSeats();
        });
    });

    function updateChosenSeats() {
        const chosenSeats = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value) // Use the 'value' attribute of the checkbox
            .join(', ');

        chosenSeatsLabel.textContent = "Chosen Seats: " + chosenSeats;
    }



});





