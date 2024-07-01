let audio1 = new Audio("https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/clickUp.mp3");

function chatOpen() {
    document.getElementById("chat-open").style.display = "none";
    document.getElementById("chat-close").style.display = "block";
    document.getElementById("chat-window1").style.display = "block";
    bringChatboxToTop();
    audio1.load();
    audio1.play();
}

function chatClose() {
    document.getElementById("chat-open").style.display = "block";
    document.getElementById("chat-close").style.display = "none";
    document.getElementById("chat-window1").style.display = "none";
    document.getElementById("chat-window2").style.display = "none";
    bringChatboxToTop();
    audio1.load();
    audio1.play();
}

function openConversation() {
    document.getElementById("chat-window2").style.display = "block";
    document.getElementById("chat-window1").style.display = "none";
    bringChatboxToTop();
    audio1.load();
    audio1.play();
}

function bringChatboxToTop() {
    document.getElementById("chat-open").style.zIndex = 9999;
    document.getElementById("chat-close").style.zIndex = 9999;
    document.getElementById("chat-window1").style.zIndex = 9999;
    document.getElementById("chat-window2").style.zIndex = 9999;
}

async function userResponse() {
    let userText = document.getElementById("textInput").value;

    if (userText.trim() === "") {
        alert("Hãy đợi trong vài giây!");
        return;
    }

    appendMessage("Bạn", userText);

    document.getElementById("textInput").value = "";
    var objDiv = document.getElementById("messageBox");
    objDiv.scrollTop = objDiv.scrollHeight;

    try {
        const response = await fetch("https://chatbox-1-cd9fd409524d.herokuapp.com/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ msg: userText }),
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        appendMessage("Nhân viên", data.response);
    } catch (error) {
        console.error("Error:", error);
        appendMessage("Nhân viên", "Xin lỗi, đã có lỗi xảy ra.");
    }
}

function appendMessage(sender, message) {
    const messageBox = document.getElementById("messageBox");
    const messageElement = document.createElement("div");
    messageElement.className = sender === "Bạn" ? "first-chat" : "second-chat";

    if (sender === "Nhân viên") {
        const circleElement = document.createElement("div");
        circleElement.className = "circle";
        messageElement.appendChild(circleElement);
    }

    const textElement = document.createElement("p");
    textElement.textContent = `${sender}: ${message}`;
    messageElement.appendChild(textElement);

    const arrowElement = document.createElement("div");
    arrowElement.className = "arrow";
    messageElement.appendChild(arrowElement);

    messageBox.appendChild(messageElement);
    messageBox.scrollTop = messageBox.scrollHeight;
}

document.addEventListener("DOMContentLoaded", function () {
    const chatOpenButton = document.getElementById("chat-open-button");
    const chatCloseButton = document.getElementById("chat-close-button");
    const chatOpenDiv = document.getElementById("chat-open");
    const chatCloseDiv = document.getElementById("chat-close");
    const chatWindow1 = document.getElementById("chat-window1");
    const chatWindow2 = document.getElementById("chat-window2");
    const sendButton = document.getElementById("send");
    const textInput = document.getElementById("textInput");

    chatOpenButton.addEventListener("click", chatOpen);

    chatCloseButton.addEventListener("click", chatClose);

    sendButton.addEventListener("click", userResponse);

    textInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();  // Prevent the form from submitting
            userResponse();
        }
    });
});
