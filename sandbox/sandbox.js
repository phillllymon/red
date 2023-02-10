console.log("oh hello");

fetch("https://seattle.craigslist.org/kit/boa/d/blaine-1977-fuji-32-cutter-rig/7585900116.html", { mode: "no-cors" }).then((res) => {
    console.log(res);
});