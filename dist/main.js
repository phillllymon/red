console.log("hello world"),fetch("https://graffiti.red/API/",{method:"POST",body:JSON.stringify({action:"signUp",username:"@realDonaldTrump",pass:"myFancyPassword"})}).then((o=>{console.log("**********************"),console.log(o),console.log("**********************"),o.json().then((o=>{console.log("---------------------"),console.log(o),console.log("---------------------")})).catch((o=>{console.log("ERROR WITH JSON STEP"),console.log(o.message)}))})).catch((o=>{console.log("ERROR"),console.log(o.message)})),console.log("Well....here I am.");