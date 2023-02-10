console.log("It was just a one time thing");

fetch("https://graffiti.red/API/", {
    method: "POST",
    body: JSON.stringify({
        action: "oneTimeThing"
    })
}).then((res) => {
    res.json().then((response) => {
        console.log(response);
    }).catch(() => {
        console.log("ERROR in json step");
    });
}).catch((err) => {
    console.log("ERROR");
    console.log(err.message);
});