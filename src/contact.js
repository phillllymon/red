const submitted = document.getElementById("submitted");
const messageBox = document.getElementById("message");
const emailBox = document.getElementById("email");
const submitButton = document.getElementById("message-submit");

submitButton.addEventListener("click", () => {
    const message = messageBox.value;
    const email = emailBox.value;
    submitted.classList.remove("hidden");
    messageBox.value = "";
    emailBox.value = "";
    fetch("https://graffiti.red/API/", {
        method: "POST",
        body: JSON.stringify({
            action: "giveFeedback",
            username: "website contact",
            email: email,
            feedback: message
        })
    });
});