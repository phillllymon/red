console.log("hello wonderful world"),fetch("https://graffiti.red/API/public/",{method:"POST",body:JSON.stringify({action:"set",name:"hello",value:"poop"})}).then((o=>{console.log("**********************"),console.log(o),console.log("**********************"),o.json().then((o=>{console.log("---------------------"),console.log(o),console.log("---------------------")})).catch((o=>{console.log("ERROR WITH JSON STEP"),console.log(o.message)}))})).catch((o=>{console.log("ERROR"),console.log(o.message)})),console.log("Well....here I am.");