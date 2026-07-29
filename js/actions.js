
function toggleSsl() {
    const toggle = document.getElementById("sslToggle");
    const container = document.getElementById("sslContainer");

    if (!toggle || !container) {
        return;
    }

    container.classList.toggle( "dbv-hidden", !toggle.checked );
}

document.addEventListener("DOMContentLoaded", toggleSsl);

function clickRow(key, id) {
    const row = rows.find(r => r[key] == id);

    if (!row) {
        console.error("Row not found");
        return;
    }

    showRowPopup(row);
}

function showRowPopup(row) {
    const popup = document.createElement("div");
    popup.className = "dbv-popup";

    const content = document.createElement("div");
    content.className = "dbv-popup-content";

    const closeButton = document.createElement("button");
    closeButton.className = "dbv-popup-close";
    closeButton.innerHTML = "&times;";

    const title = document.createElement("h3");
    title.textContent = "Row JSON";

    const textarea = document.createElement("textarea");
    textarea.readOnly = true;
    textarea.value = JSON.stringify(row, null, 4);

    content.appendChild(closeButton);
    content.appendChild(title);
    content.appendChild(textarea);

    popup.appendChild(content);

    closeButton.onclick = () => popup.remove();

    popup.onclick = (e) => {
        if (e.target === popup) {
            popup.remove();
        }
    };

    document.body.appendChild(popup);
}

function printJson() {
    if (!tableData || Object.keys(tableData).length === 0) {
        console.error("No JSON data available");
        return;
    }

    const blob = new Blob(
        [JSON.stringify(tableData, null, 2)],
        { type: "application/json" }
    );

    const url = URL.createObjectURL(blob);

    window.open(url);

    setTimeout(() => URL.revokeObjectURL(url), 1000);
}