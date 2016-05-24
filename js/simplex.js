
var numeroLoops = 1;
var MAX_LOOPS;
var STOPBYLOOP = MAX_LOOPS + 1;

// Autores RodrigoNeuber-RA:53675-1 / SandraKavakame-RA:53749-7 / TalitaMendes-RA:542741 / &copy 2016 <rodrigoneuber@icloud.com>
function simplex() {

  clear();

  MAX_LOOPS = document.getElementById('interate').value;
  montamatrix();
  while(CondicaoParada() && MaxVoltas())
    calculo();
  solucao();
  Sensibilidade()
}

function montamatrix()
{

  var z = document.getElementById('zFunction').value.split(';');
  x = [];
  var radios = document.getElementsByName("inputZtype");
  if(radios[1].checked){
    for(var j = 0; j < z.length; j++){
      var i =(z[j]*(-1));
      console.log(i);
      x.push(i);
    }
    z = x;
    console.log(z);
  }
  var restriction = document.querySelectorAll('#subjects .form-group');

  matrix = [[]];
  matrix[0][0] = 'Linha';

  for(var i = 1; i <= z.length; i++)
    matrix[0].push('x' + i);


  for(var i = 1; i <= restriction.length; i++)
    matrix[0].push('f' + i);

  matrix[0][matrix[0].length] = 'b';
  var countCol = 1;

  for(var i = 1; i <= restriction.length; i++)
  {
    matrix.push(['f' + i]);
    countCol = 1;
    var basicVars = restriction[i-1].querySelectorAll('input')[0].value.split(';');
    for(var j = 0; j < z.length; j++){
      if(basicVars[j])
        matrix[i][countCol++] = Number(basicVars[j]);
      else
        matrix[i][countCol++] = 0;
    }

    for(var j = 0; j < restriction.length; j++){
      matrix[i][countCol] = Number((matrix[i][0] == matrix[0][countCol] ? 1 : 0));
      countCol++;
    }

    matrix[i][countCol] = Number(restriction[i-1].querySelectorAll('input')[1].value);
  }
  matrix.push(['Z']);

  countCol = 1;
  for(var i = 0; i < z.length; i++){
    if(z[i])
      matrix[matrix.length - 1][countCol++] = Number(-1 * z[i]);
    else
      matrix[matrix.length - 1][countCol++] = 0;
  }

  for(var j = 0; j < restriction.length; j++)
    matrix[matrix.length - 1][countCol++] = 0;
  matrix[matrix.length - 1][countCol] = 0;

  printTable('Tabela Inicial');
}

function calculo()
{
  var zrow = matrix.length - 1;
  var columnAmount = matrix[zrow].length - 2;
  var rowAmount = matrix.length - 2;
  var entra = 0;
  var minEntraValor = Number.MAX_VALUE;

  for(var i = 1; i <= columnAmount; i++)
    if(matrix[zrow][i] < minEntraValor)
    {
      entra = i;
      minEntraValor = matrix[zrow][i];
    }

  var sai = 0;
  var iminSaiValor = Number.MAX_VALUE;

  for(var i = 1; i <= rowAmount; i++)
  {
    var bValue = matrix[i][matrix[0].length - 1];
    var colValue = matrix[i][entra];

    if(colValue <= 0)
      continue;

    var result = bValue / colValue;
    if(result < iminSaiValor)
    {
      sai = i;
      iminSaiValor = result;
    }
  }

  if(sai == 0)
  {
    numeroLoops = STOPBYLOOP;
    return;
  }

   console.log("Entra na base: " + matrix[0][entra]);
   console.log("Sai da base:" + matrix[sai][0]);

  matrix[sai][0] = matrix[0][entra];

  var pivo = matrix[sai][entra];

  for(var i = 1; i < matrix[0].length; i++)
    matrix[sai][i] = matrix[sai][i] / pivo;

  for(var row = 1; row < matrix.length; row++)
  {
    if(row == sai || matrix[row][entra] == 0)
      continue;

    var fator = -1 * matrix[row][entra];

    for(var column = 1; column < matrix[row].length; column++)
      matrix[row][column] = (matrix[sai][column] * fator) + matrix[row][column];
  }
      printTable('Iteração ' + numeroLoops++);
}

function CondicaoParada()
{
  //pega a linha de z
  var zrow = matrix.length - 1;

  //colunas verificáveis
  var columnAmount = matrix[zrow].length - 1;

  //verifica se há valor negativo
  for(var i = 1; i < columnAmount; i++)
    if(matrix[zrow][i] < 0)
      return true; //ainda há valores negativos

    return false; //parar o simplex
}

function MaxVoltas()
{
  if(numeroLoops > MAX_LOOPS)
    return false;

  return true
}

function printTable(title)
{
  var results = document.getElementById('tables');
  var htmlTitle = '<h4 class="text-center">' + title + '</h4>';
  var table = '<table class=" tamanho mdl-data-table mdl-js-data-table mdl-shadow--2dp">';
  var header = '<thead><tr>';
  for(var col = 0; col < matrix[0].length; col++)
    header += "<th>" + matrix[0][col] + "</th>";
  table += header + '</tr></thead>';
  var body = '<tbody>';
  for(var row = 1; row < matrix.length; row++)
  {
    var tr = '<tr>';
    for(var col = 0; col < matrix[row].length; col++)
      tr += '<td>' +  matrix[row][col] + '</td>';

    body += tr + '</tr>';
  }

  table += body + '</tbody>';
  results.innerHTML += htmlTitle + table + '</table><hr />';
}

function solucao()
{
  var outputDiv = document.getElementById('post-optimization');
  var header = '<h4 class="text-center">Após otimização</h4>';
  var paragrafos = '';
  for(var i = 1; i < (matrix[0].length - 1); i++)
  {
    var solucao = (matrix[0][i][0] == 'x' ? 'Produção de ' : 'Folga da restrição ') + matrix[0][i];
    var val = 0;
    for(var rowIndex = 1; rowIndex < (matrix.length - 1); rowIndex++)
      if(matrix[0][i] == matrix[rowIndex][0])
        val = matrix[rowIndex][matrix[0].length - 1];
    paragrafos += '<p>' + solucao + ' = ' + val + '</p>';
  }
  outputDiv.innerHTML = header + paragrafos;
};


function Sensibilidade()
{

  var outputDiv = document.getElementById('sensibility-analysis');
  var title = '<h4 class="text-center">Análise de Sensibilidade</h4>';
  var paragraphs = '';
  var subjects = document.querySelectorAll('#subjects .form-group');
  var subjectIndex = (matrix[0].length - subjects.length) - 1;
  for(var index = 0;index < subjects.length;index++,subjectIndex++)
  {
    var restricao = 'f'+(index+1);
    var original = Number(subjects[index].querySelectorAll('input')[1].value);
    var minDelta = Number.POSITIVE_INFINITY;
    var maxDelta = Number.NEGATIVE_INFINITY;

    var shadowPrice = matrix[matrix.length - 1][subjectIndex];


    if(shadowPrice != 0) {
    for(var rowIndex = 1; rowIndex < (matrix.length - 1); rowIndex++)
    {
      var functionRow = Number(matrix[rowIndex][subjectIndex]);
      var bRow = Number(matrix[rowIndex][matrix[0].length - 1]);

      if(functionRow == 0)
        continue;

      var delta = (-1 * bRow) / functionRow;

      if(delta > maxDelta)
        maxDelta = delta;

      if(delta < minDelta)
        minDelta = delta;
    }
    minDelta += original;
    maxDelta += original;
    paragraphs += '<tr><td>' + restricao + '</td><td>' +original + '</td><td>' + shadowPrice + '</td><td>' + minDelta + '</td><td>' + maxDelta + '</td></tr>';
  } else {
    paragraphs += '<tr><td>' + restricao + '</td><td>' +original + '</td><td>' + shadowPrice + '</td><td>Alterações são insignificantes</td></td>';
  }
}
outputDiv.innerHTML =  title + "<table class='tamanho mdl-data-table mdl-js-data-table mdl-shadow--2dp'>"+
                               "<thead><tr><th>Análise de Sensibilidade</th>"+
                                           "<th>Original</th>"+
                                           "<th>Preço Sombra</th>"+
                                           "<th>Menor</th>"+
                                           "<th>Maior</th>"+
                                      "</tr></thead>"+paragraphs+"</tables>";

}
