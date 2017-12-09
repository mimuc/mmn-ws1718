// in this file you find all custom javascript code used in index.html

$(document).ready(function () {
    var pw_input = $('#pw-input');

    // bind password field
    pw_input.on('keyup', function () {
        updateOutputs(pw_input.val(), output);
    });
});

function updateOutputs(password) {
    var output_elem = $('#output');
    var strengthbar = $('#strength-bar');
    var pw_check = zxcvbn(password);

    strengthbar.css("display", "block");
    output_elem.html(JSON.stringify(pw_check, null, 2));

    updateStrengthBar(pw_check.score);
    $("#warning").text(pw_check.feedback.warning);
    updateTimes(pw_check);
    updateSuggestions(pw_check);
}

function updateStrengthBar(score) {
    var clr_table = [
        "darkred",
        "red",
        "yellow",
        "limegreen",
        "green"
    ]

    // deactivate all bars
    for (var i = 0; i < clr_table.length; i++) {
        $('#strength-bar .bar:eq(' + i + ')').css("background-color", "gray");
    }

    // update only if some password were typed in
    // activate all passed security levels
    for (i = 0; i <= score; i++) {
        $('#strength-bar .bar:eq(' + i + ')').css("background-color", clr_table[score]);
    }
}

function updateSuggestions(pw_check) {
    var suggestions_elem = $('#suggestions');

    var result = "";
    for (var i = 0; i < pw_check.feedback.suggestions.length; i++) {
        var suggestion = pw_check.feedback.suggestions[i];
        result += "<li>" + suggestion + "</li>";
    }

    suggestions_elem.html(result);
}

function updateTimes(pw_check) {
    var online_banking_crack = $('#cracked_pw');
    var encrstr_crack = $('#cracked_encstring');

    online_banking_crack.text(pw_check.crack_times_display.online_throttling_100_per_hour);
    encrstr_crack.text(pw_check.crack_times_display.offline_slow_hashing_1e4_per_second);
}