
//display sections of a chapter when clicked
document.addEventListener("DOMContentLoaded", function () {
    let chapters = document.querySelectorAll(".chapters > li > a");

    chapters.forEach(chapter => {
        chapter.addEventListener("click", function (event) {
            event.preventDefault();

            // once a chapter is opened closes the previously opened
            document.querySelectorAll(".section").forEach(section => {
                if (section !== this.nextElementSibling) {
                    section.style.display = "none";
                }
            });

            // opens a chapter
            let submenu = this.nextElementSibling;
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        });
    });
});
//runs the python code in textareas using an API
async function runPython(codeId, outputId) {
    let code = document.getElementById(codeId).value;
    let outputElement = document.getElementById(outputId);

    outputElement.textContent = "Running...\n";

    fetch("https://emkc.org/api/v2/piston/execute", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            language: "python",
            version: "3.10.0",
            files: [{ content: code }]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.run) {
            outputElement.textContent = data.run.output || "No output received.";
        } else {
            outputElement.textContent = "Error: Invalid response.";
        }
    })
    .catch(error => {
        outputElement.textContent = "Error: Failed to fetch response.";
        console.error("Fetch error:", error);
    });
}
//when terms and conditions are opened they pop up for user
function openPopup(){
    event.preventDefault();
    document.getElementById("popup").style.display = "block";
    document.getElementById("overlay").style.display = "block";

}
//closing them
function closePopup(){
    document.getElementById("popup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
//user end validation for sign up
document.querySelector("form").addEventListener("submit",function(submit){
    let pass = document.getElementById("password").value ;
    let cpass = document.getElementById("Verify Password").value ;
    //checks if password and confirm pasword are the same
    if(pass != cpass ){
        submit.preventDefault();
        document.getElementById('error-message').textContent = "Passwords do not match";
        return; 
    }
    //check if password is atleast 8 chracters including 1 digit 
    let pattern = /^(?=.*\d).{8,}$/; //patern for 8 numbers including 1 number
    
    if(!pattern.test(pass)){
        submit.preventDefault();
        document.getElementById('error-message').textContent = "Password must be atleast 8 characters, 1 digit";
        return; 
    }
    //checks if email is in correct format:  name@mail.domain
    let email = document.getElementById('emls').value;
    let epattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if(!epattern.test(email)){
        submit.preventDefault();
        document.getElementById('error-message').textContent = "Wrong email format";
        return; 
    }
});

function toggleSidebar() {
    document.querySelector('.nav').classList.toggle('visible');
}







