
if (window.navigator.userAgent.toLowerCase().includes("mobi")) {
    document.body.innerHTML = "graffiti not for phones";
} else {
    const numPics = 7;
    let currentPic = 1;
    setInterval(() => {
        document.getElementById(`${currentPic}`).classList.add("hidden");
        currentPic++;
        if (currentPic > numPics) {
            currentPic = 1;
        }
        document.getElementById(`${currentPic}`).classList.remove("hidden");
    }, 8000);
}