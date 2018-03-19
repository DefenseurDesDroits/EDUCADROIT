var queryString = window.location.search || '';
var keyValPairs = [];
var params      = {};
queryString     = queryString.substr(1);


var pagerForm = document.getElementById('pagerForm');
if (pagerForm){
  pagerForm.onsubmit = function(){
    var value = document.getElementById('page').value - 1;
    document.getElementById('page').value = value;
    if (queryString.length){
       keyValPairs = queryString.split('&');
       for (pairNum in keyValPairs){
          var key = keyValPairs[pairNum].split('=')[0];
          if (!key.length) continue;
          if (typeof params[key] === 'undefined')
             params[key] = [];
          if (key != 'page'){
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = key;
            input.value = keyValPairs[pairNum].split('=')[1];
            pagerForm.appendChild(input);
          }
       }
    }
    pagerForm.submit();
  }
}

function offlineResource(event){
  var element = document.getElementById('offline-resource');
  element.style.cursor = 'progress';
  jQuery.ajax({
     url : element.getAttribute("data-link"),
     type : 'GET',
     dataType : 'json',
     success : function(json, statut){
      element.innerHTML = json.message;
     },
     error : function(resultat, statut, erreur){
      element.innerHTML = "Une erreur s'est produite. Veuillez réessayer ulterieurement";
     },
     complete : function(resultat, statut){
      element.style.cursor = 'pointer';
     }
  });
}
