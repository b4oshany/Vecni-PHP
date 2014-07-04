$("#DateCountdown").TimeCircles({
    "animation": "smooth",
    "bg_width": 1.2,
    "fg_width": 0.1,
    "circle_bg_color": "#60686F",
    "time": {
        "Days": {
            "text": "Days",
            "color": "#FFCC66",
            "show": true
        },
        "Hours": {
            "text": "Hours",
            "color": "#99CCFF",
            "show": true
        },
        "Minutes": {
            "text": "Minutes",
            "color": "#BBFFBB",
            "show": true
        },
        "Seconds": {
            "text": "Seconds",
            "color": "#FF9999",
            "show": true
        }
    }
});

$("[name='register'][type='button']").click(function(){
    $("#login-register form.login").hide("slow");
    $("#login-register .modal-title").html("Register");
    $("#login-register form.register").show("slow");
})

$("[name='signin'][type='button']").click(function(){
    $("#login-register form.register").hide("slow");
    $("#login-register .modal-title").html("Sign In");
    $("#login-register form.login").show("slow");
})