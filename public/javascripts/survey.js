$(document).ready(function() {
    // Submit answers to /survey endpoint
    $("#survey-form").submit(function(event) {
        event.preventDefault();

        // TODO: form validation
        $.post('/survey', {
            'email': $("#subscription-email").val(),
            'recruiter-q2-answer': $("#recruiter-q2-answer option:selected").text(),
            'recruiter-q3-answer': $("#recruiter-q3-answer").val(),
            'recruiter-q4-answer': $("#recruiter-q4-answer").val(),
            'engineer-q2-answer': $("#engineer-q2-answer option:selected").text(),
            'engineer-q3-answer': $("#engineer-q3-answer option:selected").text(),
            'engineer-q4-answer': $("#engineer-q4-answer").val(),
            'ajax': 1
        }, function(data) {
            if (data.status == 'success') {
                // TODO: show success survey submission
            } else {
                // TODO: failed to submit survey
            }
        }, 'json');
    });

    var radios = $("#first-question :input");

    radios[0].onclick = function() {
        $(".recruiter-questions").show();
        $(".engineer-questions").hide();
    }
    radios[1].onclick = function() {
        $(".recruiter-questions").hide();
        $(".engineer-questions").show();
    }
});