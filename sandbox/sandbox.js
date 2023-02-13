console.log("oh hello");

const username = "MrMagoo";
const token = "$2y$10$TJLvjVSeKL4g.C9DRwMFS.N0tFP9uESLtskzfDM2T78Y2DZxourUW";

fetch("https://graffiti.red/API/getIcon.php", {
    method: "POST",
    body: JSON.stringify({
        icon: "cl"
    })
}).then((res) => {
    console.log(res);
    res.json().then((image) => {
        console.log(image);
    });
}).catch((err) => {
    console.log("ERROR");
    console.log(err.message);
});

function checkLogin(username, token) {
    return makeCallToAPI("checkLogin", { username: username, token: token });
}

function getConversations(username, token) {
    return makeCallToAPI("getConversations", {
        username: username,
        token: token
    }, false);
}

function makeCallToAPI(action, inputs) {
    return new Promise((resolve) => {
        fetch("https://graffiti.red/API/", {
            method: "POST",
            body: JSON.stringify({
                action: action,
                ...inputs
            })
        }).then((res) => {
            console.log(res);
            res.json().then((response) => {
                resolve(response);
            }).catch((err) => {
                resolve({
                    status: "fail",
                    message: err.message
                });
            });
        }).catch((err) => {
            resolve({
                status: "fail",
                message: err.message
            });
        });
    });
}