/**
 * Created by Camiel on 30-Jan-17.
 */

$( "#datum" ).change(function() {
    loadAvailableClassrooms($("#datum").val(), $("#start_tijd").val(), $("#eind_tijd").val())
});
$( "#start_tijd" ).change(function() {
    loadAvailableClassrooms($("#datum").val(), $("#start_tijd").val(), $("#eind_tijd").val())
});
$( "#eind_tijd" ).change(function() {
    loadAvailableClassrooms($("#datum").val(), $("#start_tijd").val(), $("#eind_tijd").val())
});

loadAvailableClassrooms($("#datum").val(), $("#start_tijd").val(), $("#eind_tijd").val());

function loadAvailableClassrooms(date, start, end) {
    console.log('loading classrooms...');
    if (date != "" && start != "" && end != "") {
        console.log(date, start, end);
        jQuery.ajax({
            type: "POST",
            url: "../php/available_classrooms.php",
            dataType: 'json',
            data: {
                date: date,
                start: start,
                end: end
            },

            success: function (obj, textstatus) {
                if (!('error' in obj) && obj.classroomsHtml != null) {
                    document.getElementById('lokaal').innerHTML = obj.classroomsHtml;
                    document.getElementById('lokaal').setAttribute('style', 'color: black');
                } else {
                    console.log(obj.error);
                    document.getElementById('lokaal').innerHTML = "<option disabled selected value>Geen lokalen beschikbaar op de gegeven tijden. Kies een andere tijd/datum.</option>";
                    document.getElementById('lokaal').setAttribute('style', 'color: darkred');
                }
            },
            error: function(jqXHR, textStatus) {
                document.getElementById('lokaal').innerHTML = "<option disabled selected value>Er ging iets fout. Probeer het later nog eens.</option>";
                document.getElementById('lokaal').setAttribute('style', 'color: darkred');
                if(textStatus === 'timeout') {
                    console.log('Failed from timeout');
                } else {
                    console.log("Unable to load classroom info..");
                    console.log(textStatus);
                }
            },
            complete: function(jqXHR, textStatus) {
            },
            timeout: 30000
        });
    }
}