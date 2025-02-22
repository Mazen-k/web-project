document.addEventListener("DOMContentLoaded", function () {
    let chapters = document.querySelectorAll(".chapters > li > a");

    chapters.forEach(chapter => {
        chapter.addEventListener("click", function (event) {
            event.preventDefault();

            // Close all other open sections
            document.querySelectorAll(".section").forEach(section => {
                if (section !== this.nextElementSibling) {
                    section.style.display = "none";
                }
            });

            // Toggle current section
            let submenu = this.nextElementSibling;
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        });
    });
});
function runPython() {
    let code = document.getElementById("python-code").value;
    let outputElement = document.getElementById("output");

    outputElement.textContent = "Running...\n";

    fetch("https://pythoncompilerapi.com/run", {  // Replace with an actual Python execution API
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        outputElement.textContent = data.output;
    })
    .catch(error => {
        outputElement.textContent = "Error running code!";
    });
}
