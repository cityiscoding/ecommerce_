window.addEventListener('DOMContentLoaded', function () {
    const chatbot = document.getElementById('chatbot-container');

    // Hàm đưa chatbot lên trên cùng
    function bringChatbotToTop() {
        chatbot.style.zIndex = 9999;
    }

    // Gọi hàm khi có sự kiện thay đổi trên trang
    window.addEventListener('scroll', bringChatbotToTop);
    window.addEventListener('resize', bringChatbotToTop);
    window.addEventListener('click', bringChatbotToTop);
    window.addEventListener('mouseover', bringChatbotToTop);
});
