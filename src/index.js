console.log("hello world");

fetch("https://graffiti.red/API/", {
    method: "POST",
    body: JSON.stringify({
        action: "createPost",
        username: "MrMagoo",
        token: "bullshit token",
        url: "https://www.google.com",
        content: "goooogle nooodle pooooooooooooooodle"
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