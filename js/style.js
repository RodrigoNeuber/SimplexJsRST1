var jq=jQuery.noConflict();
var subjectCount = 0;


//<!-- Autores RodrigoNeuber-RA:53675-1 / SandraKavakame-RA:53749-7 / TalitaMendes-RA:542741 / &copy 2016 <rodrigoneuber@icloud.com> 
function addSubjects()
{
  var newSubject = document.createElement('div');
  newSubject.id = "restricao"+subjectCount;
  var subjectInput = "<div class='input-group'>";
  subjectInput += "<span class='col s1'><strong>F" + (subjectCount + 1) + "<strong></span>";
  subjectInput += "<input type='text' class='tooltipped col s5 ' placeholder='Para digitar: 3x1 + 5x2, digite: 3;5' id='subject"+ subjectCount + "'/>";
  var subjectSecondInput = "<span class='col s1 sinal valign-wrapper'><strong>&le;<strong></span>";
  subjectSecondInput += '<input type="text" class="form-control col s2" id="subjectTyped' + subjectCount + '"/>';
  subjectSecondInput += "</div>";
  var del = "<a class='btn-floating waves-effect waves-light  teal accent-4 delete"+ subjectCount +"' onclick='removeSubjects("+ newSubject.id+");''><i class='material-icons'>delete</i></a></div>"
  var add = "<a class='btn-floating waves-effect waves-light red' onclick='addSubjects();'><i class='material-icons'>add</i></a>"
  var butons = "<div class='col s3 '>"+ del +"</div>";
  newSubject.innerHTML = "<div class='form-group col s12'>" + subjectInput + subjectSecondInput + butons+"</div>";
  document.getElementById("subjects").appendChild(newSubject);
  subjectCount++;

  if(subjectCount > 0){
    jQuery('.init').hide();
  }
  jQuery('.delete0').addClass("disabled");
}

function removeSubjects(restricao)
{
  restricao.remove();
  subjectCount--;
}
(function($) {
  $(document).ready(function(){
    $('.parallax').parallax();
  });

  $(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
  });

})(jQuery);

function clear(){
  jQuery("#tables").empty();
  console.log("Comando Correto");
}
