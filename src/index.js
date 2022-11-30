console.log("hello world");

fetch("https://graffiti.red/API/", {
    method: "POST",
    body: JSON.stringify({
        action: "logIn",
        username: "MrMagoo",
        token: "dwPoqivIDFbVsQ%w3yE!x(lTO%RRucPNl)WKYF2TrUlGZ7qewCoM&2j5eOcRv@eYLZjz0"
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

// fetch("https://graffiti.red/API/public/", {
// // fetch("http://localhost:8000/API/public/", {
//     method: "POST",
//     body: JSON.stringify({
//         action: "retrieve",
//         name: "howdoigetoutofhere",
//         // value: "dunno, sir",
//     })
// }).then((res) => {

//     console.log("**********************");
//     console.log(res);
//     console.log("**********************");

//     res.json().then((newRes) => {
//         console.log("---------------------");
//         console.log(newRes);
//         console.log("---------------------");
//     }).catch((err) => {
//         console.log("ERROR WITH JSON STEP");
//         console.log(err.message);
//     });
// }).catch((err) => {
//     console.log("ERROR");
//     console.log(err.message);
// });

console.log("Well....here I am.");