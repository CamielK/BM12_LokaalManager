
function loadClassroomInfo(classroom_name) {
    
	jQuery.ajax({
		type: "POST",
		url: "../php/classroom_info.php",
		dataType: 'json',
		data: {classroom_name: classroom_name},
		
		success: function (obj, textstatus) {
	    if (!('error' in obj)) {
	        document.getElementById('classroom_row_' + classroom_name).innerHTML = obj.rowHtml;
	        if (obj.modalHtml !== null) {
                document.getElementById('modal_container').innerHTML = document.getElementById('modal_container').innerHTML + obj.modalHtml;
            }
	    } else {
	      console.log(obj.error);
            document.getElementById('classroom_row_' + classroom_name).innerHTML = classroom_error(classroom_name);
	    }
		},
		error: function(jqXHR, textStatus) {
            document.getElementById('classroom_row_' + classroom_name).innerHTML = classroom_error(classroom_name);
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

window.onload = function () { 
    
    loadClassroomInfo('B3206');
    loadClassroomInfo('B3208');
    loadClassroomInfo('B3210');
    loadClassroomInfo('B3212');
    loadClassroomInfo('B3214');
    loadClassroomInfo('B3216');
    
    window.setInterval(function(){
        //load classroom info rows when no modals are opened
        
        modal_opened = false
        var nodes = document.getElementById('modal_container').childNodes
        for(i=0; i<nodes.length; i+=1) {
            if(nodes[i].className === 'modal fade in') {
                modal_opened = true
            }
        }
        
        if (!modal_opened) {
            document.getElementById('modal_container').innerHTML = ''
            loadClassroomInfo('B3206');
            loadClassroomInfo('B3208');
            loadClassroomInfo('B3210');
            loadClassroomInfo('B3212');
            loadClassroomInfo('B3214');
            loadClassroomInfo('B3216');
        }
        
    }, 5000);
    
}


function classroom_error(classroom_name) {
    return "<th scope='row'>"+classroom_name+"</th><td colspan='3'><div class='alert alert-info' style='margin-bottom: 1px;height: 30px;line-height:30px;padding:0px 15px;'>Unable to load classroom info at this moment. Please try again later</div></td><td><button type='button' class='btn btn-info disabled'>Reserveren</button></td>";
}