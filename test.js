const action = "signUp";
const inputs = {
    username: "poop",
    avatar: "&#128100;",
    pass: "password123",
    email: "rparkerharris@gmail.com"
};

fetch("https://graffiti.red/API/", {
    method: "POST",
    body: JSON.stringify({
        action: action,
        ...inputs
    })
}).then((res) => {
    console.log(res);
    res.json().then((result) => {
        console.log(result);
    });
});