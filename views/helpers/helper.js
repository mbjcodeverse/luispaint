
const _helper = {};

// Accept all characters
_helper.allChars = function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    return (charCode >= 40 && charCode <= 130) || charCode == 32 ? true : false;
};

// Checks for valid numeric key input
_helper.isNumeric = function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    return charCode >= 48 && charCode <= 57 ? true : false;
};

// Checks for valid numeric key input (with dash)
_helper.isNumericDash = function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    return (charCode >= 48 && charCode <= 57) || charCode == 45 ? true : false;
};

// Checks for valid string key input (A-Z)
_helper.isString = function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    return (charCode >= 65 && charCode <= 90) ||
        (charCode >= 97 && charCode <= 122) ||
        charCode == 32 ||
        charCode == 164 ||
        charCode == 165
        ? true
        : false;
};

// Checks for valid string key input (A-Z) with some special characters
_helper.isStringSpeChar = function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    return (charCode >= 65 && charCode <= 90) ||
        (charCode >= 97 && charCode <= 122) ||
        (charCode >= 44 && charCode <= 46) ||
        charCode == 32 ||
        charCode == 35 ||
        charCode == 39 ||
        charCode == 164 ||
        charCode == 165
        ? true
        : false;
};

// Populate options for select/dropdown element
_helper.populateSelect = function (dom_element, data) {
    let template = ``;
    if (data.length != 0) {
        template = `<option value="-">- Select -</option>`;
        data.forEach((item) => {
            template += `<option value="${item.code}">${item.desc}</option>`;
        });
    }
    // clean table first
    while (dom_element.firstChild) {
        dom_element.removeChild(dom_element.firstChild);
    }

    // repopulate table with new content
    dom_element.innerHTML = template;
};

_helper.ajaxRequest = function (url, method, data, callback) {
    // _helper.loader('on');
    $.ajax({
        url,
        method,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        data,
        dataType: 'json'
    }).done(function (data, textStatus, jqXHR) {
        callback(jqXHR.status, data, jqXHR.statusText);
    }).fail(function (jqXHR) {
        callback(jqXHR.status, null, jqXHR.statusText);
    }).always(function () {
        // _helper.loader('off');
    });
};

// Sets the loader ON or OFF
_helper.loader = function (status) {
    var el = document.querySelector('.loader-transparent');
    var loader = (document.querySelector('.loader')) ? document.querySelector('.loader') : false;
    if (status === 'on') {
        var width = el.parentElement.offsetWidth;
        var height = el.parentElement.offsetHeight;
        el.style.width = width + 'px';
        el.style.height = height + 'px';
        el.style.display = 'block';
        (loader) ? loader.style.display = 'block' : null;
    } else {
        el.style.display = 'none';
        (loader) ? loader.style.display = 'none' : null;
    }
};

// function numberWithCommas(num) {
//     var parts = num.toString().split(".");
//     parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//     return parts.join(".");
// }

function numberWithCommas(num) {
    var n = parseFloat(num).toFixed(2);
    var parts = n.toString().split(".");
    var number = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "." + parts[1];
    return number;
}

function getCurrentTime(){
    // -- With Seconds --
    // const date = new Date();
    // let current_time = date.toLocaleTimeString();
    // var stime = current_time.substring(0, 5) + current_time.substring(9, 11);
    // return stime;

    // -- Without Seconds --
    const date = new Date();
    let current_time = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    return current_time;
}

function twodigityear(){
    const date = new Date();
    let year = date.getFullYear();
    let digit_year = year.toString().substr(-2);
    return digit_year;
}

// .modal-header - myown.css
$(".allow-modal-drag").draggable({
    handle: ".modal-header"
})

// Disable Function Keys - Browser
$(window).keydown(function(event){
    if((event.keyCode >= 112)&&(event.keyCode <= 123)) {
      event.preventDefault();
      return false;
    }
});

// function navigabletable($table){
//   var $table = $(this);
//   var $active = $('input:focus,select:focus',$table);
//   var $next = null;
//   var focusableQuery = 'input:visible,select:visible,textarea:visible';
//   var position = parseInt( $active.closest('td').index()) + 1;
//   console.log('position :',position);
//   switch(e.keyCode){
//       case 37: // <Left>
//           $next = $active.parent('td').prev().find(focusableQuery);   
//           break;
//       case 38: // <Up>                    
//           $next = $active
//               .closest('tr')
//               .prev()                
//               .find('td:nth-child(' + position + ')')
//               .find(focusableQuery)
//           ;
          
//           break;
//       case 39: // <Right>
//           $next = $active.closest('td').next().find(focusableQuery);            
//           break;
//       case 40: // <Down>
//           $next = $active
//               .closest('tr')
//               .next()                
//               .find('td:nth-child(' + position + ')')
//               .find(focusableQuery)
//           ;
//           break;
//   }       
//   if($next && $next.length)
//   {        
//       $next.focus();
//   }
// }