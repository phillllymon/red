console.log("hello wonderful world");

fetch("https://graffiti.red/API/public/", {
// fetch("http://localhost:8000/API/public/", {
    method: "POST",
    body: JSON.stringify({
        action: "set",
        name: "hello",
        value: "world",
    })
}).then((res) => {

    console.log("**********************");
    console.log(res);
    console.log("**********************");

    res.json().then((newRes) => {
        console.log("---------------------");
        console.log(newRes);
        console.log("---------------------");
    }).catch((err) => {
        console.log("ERROR WITH JSON STEP");
        console.log(err.message);
    });
}).catch((err) => {
    console.log("ERROR");
    console.log(err.message);
});

console.log("Well....here I am.");