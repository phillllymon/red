console.log("hello world");

fetch("https://graffiti.red/API", {
    method: "POST",
    body: JSON.stringify({
        action: "createUser",
        username: "poop",
        pass: "password123"
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