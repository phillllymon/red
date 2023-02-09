const numPics = 7;

let currentPic = 1;

setInterval(() => {
    document.getElementById(`${currentPic}`).classList.add("hidden");
    currentPic++;
    if (currentPic > numPics) {
        currentPic = 1;
    }
    document.getElementById(`${currentPic}`).classList.remove("hidden");
}, 5000);