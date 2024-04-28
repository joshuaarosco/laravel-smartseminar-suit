$(".chat-bubble-btn").on("click", function(){
    $(".chat-box").css("display", "block");
    var body = document.querySelector('.chat-body');
    let height = 0;
    if(body.scrollHeight == 0){
        height = 500;
    }else{
        height = body.scrollHeight;
    }
    
    body.scrollTop = height;
});
$(".chat-close").on("click", function(){
    $(".chat-box").css("display", "none");
    $(".book-box").css("display", "none");
    $(".faq-box").css("display", "none");
});
$(".faq-btn").on("click", function(){
    $(".faq-box").css("display", "block");
    $(".book-box").css("display", "none");
});
$(".faq-close").on("click", function(){
    $(".faq-box").css("display", "none");
});
$(".book-btn").on("click", function(){
    $(".book-box").css("display", "block");
    $(".faq-box").css("display", "none");
});
$(".book-close").on("click", function(){
    $(".book-box").css("display", "none");
});