<?php
/*
Plugin Name: Chatbox Integration
*/

function chatbot_integration_scripts() {
    wp_enqueue_style('chatbot-css', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('chatbot-js', plugin_dir_url(__FILE__) . 'assets/js/chat.js', array(), null, true);
    wp_enqueue_script('chatbox-js', plugin_dir_url(__FILE__) . 'chatbox.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'chatbot_integration_scripts');

function chatbot_integration_shortcode() {
    return '
    <div class="chat-bar-open" id="chat-open">
        <button id="chat-open-button" type="button" class="collapsible close" onclick="chatOpen()">
            <img src="' . plugin_dir_url(__FILE__) . 'assets/images/Sparrow Bird.png" alt="Sparrow Bird image" />
        </button>
    </div>

    <div class="chat-bar-close" id="chat-close">
        <button id="chat-close-button" type="button" class="collapsible close" onclick="chatClose()">
            <i class="material-icons-outlined">❌</i>
        </button>
    </div>

    <div class="chat-window" id="chat-window1">
        <div class="start-conversation">
            <h1>Hãy bắt đầu với chatbox trả lời tự động</h1>
            <br />
            <p>Trả lời nhanh chóng sau vài giây.</p>
            <button class="new-conversation" type="button" onclick="openConversation()">
                <span>Bắt đầu chat 📩</span>
            </button>
        </div>
    </div>

    <div class="chat-window2" id="chat-window2">
        <div class="message-box" id="messageBox">
            <div class="hi-there">
                <p class="p1">🗨️🤖💬</p>
            </div>
        </div>
        <div class="input-box">
            <div class="write-reply">
                <input class="inputText" type="text" id="textInput" placeholder="Hãy nói gì đó..." />
            </div>
            <div class="send-button">
                <button type="submit" class="send-message" id="send" onclick="userResponse()">
                    <i class="material-icons-outlined"> Gửi </i>
                </button>
            </div>
        </div>
    </div>';
}
add_shortcode('chatbot_integration', 'chatbot_integration_shortcode');

function chatbot_integration_add_to_footer() {
    echo do_shortcode('[chatbot_integration]');
}
add_action('wp_footer', 'chatbot_integration_add_to_footer');
?>