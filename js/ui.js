// Lack of console handler
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});
    while (length--) {
        method = methods[length];
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Very simple example validation
(function () {
'use strict';
var inputs = document.querySelectorAll("input:not([type=hidden])");
var err = document.getElementsByClassName("error");

for (var i =0;i < inputs.length;i++) {
    inputs[i].addEventListener("change", function() {

    if (this.value == "") {
            this.parentElement.classList.add("show");
            this.classList.add("notValid"); 
    }
        else {
            this.parentElement.classList.remove("show");
            this.classList.remove("notValid"); 
        }
    });
}

inputs[5].addEventListener("change", function() {
    if (this.value !== inputs[4].value) {
     inputs[5].parentElement.classList.add("show");
     this.classList.add("notValid"); 
    }
    else {
     inputs[5].parentElement.classList.remove("show");
     this.classList.remove("notValid"); 
    }
});

inputs[3].addEventListener("change", function() {
    if (!this.value.includes("@")) {
     this.parentElement.classList.add("show");
     this.classList.add("notValid"); 
    }
    else {
     inputs[3].parentElement.classList.remove("show");
     this.classList.remove("notValid");
     checkIfEmailExists();
    }
});



// Prevent HTML5 ugly bubbles  
document.getElementsByTagName("form")[0].addEventListener("invalid", function( event ) {
        event.preventDefault();
    }, true );


var subBtn = document.getElementById("subBtn");

subBtn.addEventListener("click", function() {
for (var j =0;j < inputs.length;j++) {
    if (inputs[j].value == "") {
        inputs[j].classList.add("notValid"); 
        inputs[j].parentElement.classList.add("show");
        return false;
    }
}
})

})();

