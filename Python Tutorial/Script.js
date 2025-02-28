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
function openPopup(){
    event.preventDefault();
    document.getElementById("popup").style.display = "block";
    document.getElementById("overlay").style.display = "block";

}
function closePopup(){
    document.getElementById("popup").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}


