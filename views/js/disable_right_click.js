$(function() {
	document.addEventListener("contextmenu", function(event){
	event.preventDefault();
	// alert('Right Click is Disabled');    
	}, false);

	// localStorage.openpages = Date.now();
	// window.addEventListener('storage', function(e){
	// 	if (e.key == "openpages"){
	// 		localStorage.page_available = Date.now();
	// 	}

	// 	if (e.key == "page_available"){
	// 		alert("one more page already open");
	// 	}
	// },false);
});