$(document).ready(function() {
    var $form = $('#survey-form');
    var $tooltip;

    // Submit answers to /survey endpoint
    $form.submit(function(event) {
        event.preventDefault();

        var type, answer2, answer3, answer4;
        if ($("#recruiter-radio").is(":checked")) {
            type = "recruiter";
            answer2 = $("#recruiter-q2-answer option:selected").text();
            answer3 = $("#recruiter-q3-answer").val();
            answer4 = $("#recruiter-q4-answer").val();
        } else {
            type="engineer";
            answer2 = $("#engineer-q2-answer option:selected").text();
            answer3 = $("#engineer-q3-answer option:selected").text();
            answer4 = $("#engineer-q4-answer").val();
        }

        // Do form validation for answers
        if (type.length == 0 || answer2.length == 0 || answer3.length == 0 || answer4.length == 0) {
            changeSurveyFormState('error', "Please complete all survey questions before submitting.");
        } else {
            $.post('/survey', {
                'email': $("#subscription-email").val(),
                'type': type,
                'answer2' : answer2,
                'answer3' : answer3,
                'answer4' : answer4,
                'ajax': 1
            }, function(data) {
                if (data.status == 'success') {
                    changeSurveyFormState('success', "Thank you for submitting your survey!");
                } else {
                    changeSurveyFormState('error', "There was an error submitting your survey.");
                }
            }, 'json');
        }
    });

    function changeSurveyFormState(type, message) {
        if (type == 'normal') {
            hideSurveyTooltip();
        } else {
            renderSurveyTooltip(type, message);
        }
    }

    function renderSurveyTooltip(type, message) {
        var offset;

        if (!$tooltip) {
            $tooltip = $('<p id="survey-tooltip" class="subscription-tooltip"></p>').appendTo($form);
        }

        if (type == 'success') {
            $tooltip.removeClass('error').addClass('success');
        } else {
            $tooltip.removeClass('success').addClass('error');
        }

        $tooltip.text(message).fadeTo(0, 0);
        offset = $tooltip.outerWidth() / 2;
        $tooltip.css('margin-left', -offset).animate({top: '100%'}, 200).dequeue().fadeTo(200, 1);
    }

    function hideSurveyTooltip() {
        if ($tooltip) {
            $tooltip.animate({top: '120%'}, 200).dequeue().fadeTo(100, 0);
        }
    }

    var radios = $("#first-question :input");

    radios[0].onclick = function() {
        $(".recruiter-questions").show();
        $(".engineer-questions").hide();
        hideSurveyTooltip();
    }
    radios[1].onclick = function() {
        $(".recruiter-questions").hide();
        $(".engineer-questions").show();
        hideSurveyTooltip();
    }
});