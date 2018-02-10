var pictures = [
    "https://app-layout-assets.appspot.com/assets/bg1.jpg",
    "https://app-layout-assets.appspot.com/assets/bg2.jpg",
    "https://app-layout-assets.appspot.com/assets/bg3.jpg",
    "https://app-layout-assets.appspot.com/assets/bg4.jpg"
];

var pointer = 0;

document.addEventListener("DOMContentLoaded", function (ev) {
    console.log("loaded");
    var leftButton = document.getElementById("left-btn");
    var rightButton = document.getElementById("right-btn");

    leftButton.addEventListener("click", function (e) {
        goToNextPicture("left");
    });

    rightButton.addEventListener("click", function (e) {
        goToNextPicture("right");
    });
});

function goToNextPicture(direction) {
    switch (direction) {
        case("right"):
            pointer = (pointer < pictures.length - 1) ? pointer + 1 : 0;
            break;
        case("left"):
            pointer = (pointer > 0) ? pointer - 1 : 3;
            break;
        default:
            console.error("invalid direction");
    }

    updateView();
}

function updateView() {
    var counter = document.getElementById("counter");
    var picture = document.getElementById("picture");

    if (counter && picture) {
        counter.innerText = pointer + 1;
        picture.setAttribute("src", pictures[pointer]);
    } else {
        console.error("counter or picture div not found");
    }
}