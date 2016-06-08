<html>
<head>
	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8" >
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-indigo.min.css" />
	<link rel="stylesheet" type="text/css" href="resultado.css">
	<title>Resultado Simplex</title>
	<script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</head>
<body>
	
<div class=" mdl-layout mdl-js-layout mdl-layout--fixed-header ">
			<header class="mdl-layout__header">
				<div class="mdl-layout__header-row">
					<span align="center" class="mdl-layout-title">RESULTADO SIMPLEX R.S.T</span>
				</div>
			</header>

	<?php 
	
	//Declaração de Arrays
		$tabela;
		$bstabela;
		$colunatab; 
		$tdbase; //tabela de variaveis basicas.
		$arrayfunc;//array final da funcao objetiva
		$arrayfinal;
	
		if(!isset($_POST['funcao']) && !isset($_POST['sa']) && !isset($_POST['suj']) && !isset($_POST['sa']) && !isset($_POST['qtdeit']))
		{	echo "<b><center>Dados Incorretos corretamente, na página principal.php</center></b>"; die;	}
	
		$funcao = $_POST['funcao'];
		$sa = $_POST['sa'];
		$valorarrfinal = $_POST['suj'];
		$qtdeit = $_POST['qtdeit'];
	
		echo "<td><center>";
		echo "<h4><b><center>Informações Iniciais:</center></b></h4><hr>";
		
		PrintaInicio($funcao, $sa, $valorarrfinal, $qtdeit);
		
	//Funcao objetiva:
		$replacfunc = str_replace(" ", "", $funcao);
		$funcexp = explode("+", $replacfunc);	
		//Função deve ser preenchida, entao...
		if(!empty($funcexp)) 
		{
			for($i=0; $i<count($funcexp); $i++) 
			{
				$value = explode("x", $funcexp[$i])[0];
				$indice = explode("x", $funcexp[$i])[1] - 1;
				$arrayfunc[$indice] = $value;
			}
		}
		
	//Definir bases
		$indice=0;
		for($i=0; $i<count($arrayfunc);$i++) 
		{
			$tdbase[$indice][0] = "x".($i+1);
			$tdbase[$indice][1] = 0;
			$indice++;
		}
		for($i=0; $i<count($valorarrfinal); $i++) 
		{
			$tdbase[$indice][0] = "f".($i+1);
			$tdbase[$indice][1] = $valorarrfinal[$i]; $indice++;
		}
	
		//Criação de tabela
		$finalx = 0;
		for($x=0; $x<count($valorarrfinal); $x++) 
		{
			$bstabela[$x] = $tdbase[$x +(count($tdbase)- count($valorarrfinal))][0];
			$finalx = $x;
		}
			$bstabela[$finalx + 1] = "z";
		
		$finalx=0;
		for($x=0; $x<count($tdbase) ; $x++) 
		{
			$colunatab[$x] = $tdbase[$x][0];
			$finalx = $x;
		}
		$colunatab[$finalx + 1] = "b";
	
	//Regras:
		for($i=0; $i<count($sa);$i++) 
		{
			$replacrules = str_replace(" ", "", $sa[$i]);
			$expdrul = explode("+", $replacrules);
			
			for($j=0; $j<count($expdrul); $j++) 
			{
				$value = explode("x", $expdrul[$j])[0];
				$indice = explode("x", $expdrul[$j])[1] - 1;
				$arrayfinal[$i][$indice] = $value;
			}
		}
		
	//Montando a tabela:
		$firstx = -1;
		$firsty = -1;
		$firstb = -1;
		$funcaoum = -1;
		for($x=0; $x<count($bstabela); $x++) 
		{
			for($y=0; $y<count($colunatab); $y++) 
			{
				//linha z
				//Verificações:
				if($bstabela[$x] == "z")
				{
					if(strpos($colunatab[$y],'f') !== false)//verifica se possui 'f'
					{
						$tabela[$x][$y]=0;
					}
	
					if(strpos($colunatab[$y],'x') !== false)
					{
						if($funcaoum == -1)
						{$funcaoum = $y;}
						$tabela[$x][$y] = $arrayfunc[$y-$funcaoum] * -1;
					}
					//definindo B
					if($colunatab[$y] == "b")
					{	$tabela[$x][$y] = 0;}
				}
				else
				{
					if($y<=count($tdbase))
					{
						if($bstabela[$x] == $colunatab[$y])
							$tabela[$x][$y] = 1;
						else
							$tabela[$x][$y] = 0;
					}
					if(strpos($colunatab[$y],'x') !== false)//verifica se possui x
					{
						if($firstx == -1)
						{	$firstx = $x; }
						if($firsty == -1)
						{	$firsty = $y;	}
						
						if(!isset($arrayfinal[$x - $firstx][$y - $firsty]))
							$tabela[$x][$y] = 0;
						else					
							$tabela[$x][$y] = $arrayfinal[$x - $firstx][$y - $firsty];
					}
	
					if($colunatab[$y] == "b")
					{
						if($firstb == -1)
						 {	$firstb = $x; }
						$tabela[$x][$y] = $tdbase[$x - $firstb + count($tdbase) - count($valorarrfinal)][1];
					}
				}
			}
		}
		
	//Mostrando a tabela:
		echo "<hr>";
		echo "<h4><center><b>Tabela Inicial:</center></b></h4>";
		
		PrintaTabela($bstabela, $colunatab, $tabela);
		echo "<br/>";
		$contador = 0;
		do
		{
			$letrax; $letray;
			echo "<center><b><hr><i>Número desejado de iterações: <b><font color='blue'>".($contador + 1)."</b></b></i></font></center>";
			
			$menorindicey = -1;
			$menorvalor = 1000;
			for($x=0; $x<count($bstabela); $x++) 
			{
				for($y=0; $y<count($colunatab) - 1; $y++)
				{
					if($tabela[$x][$y] <= $menorvalor)
					{
						$menorvalor = $tabela[$x][$y];
						$menorindicey = $y;
					}
				}
			}
			echo "<center><hr><i><b>Menor valor encontrado na tabela: <font color='blue'>".$menorvalor."</b></i></font></center>";
			$menorvalbase = array();
			for($x=0; $x<count($bstabela)-1; $x++) //-1 pra nao contar 'Z'
			{
				for($y=0 ;$y<count($colunatab)-1; $y++)
				{
					if($menorindicey == $y)
					{
						if($tabela[$x][$y] == 0)
						{
							$menorvalbase[$x][0] = null;
						}
						else
						{
							$menorvalbase[$x][0] = ($tabela[$x][count($colunatab)- 1]/$tabela[$x][$y]);
							$menorvalbase[$x][1] = $bstabela[$x];
						}
					}
				}
			}
	
			echo "<center><hr><i>Efetuando divisão: </i>";
			PrintaTabelaMenorValor($menorvalbase);
			echo "</center>";
	//gerar menor valor na tabela
			$menorvalbaseX = -1;		
			$menorvalentra = 25000;
			for($x=0; $x<count($menorvalbase); $x++) 
			{
				if($menorvalbase[$x][0] !== null)
				{
					if($menorvalbase[$x][0] <= $menorvalentra)
					{
						$menorvalentra = $menorvalbase[$x][0];
						$menorvalbaseX = $x;
						$letrax = $menorvalbase[$x][1];
					}
				}			
			}
			$letray = $colunatab[$menorindicey];
	
			if($menorvalentra == 25000)
			{ 		echo "<h3><b>Solução impossível.</b></h1>"; die();		}	
			
			echo "<center><b>Valor é o: <font color='blue'>".$menorvalentra."</b></font></center>";
			echo "<center><hr>Entra: <font color='blue'>".$letray. "</font>; Sai: <font color='blue'>". $letrax."</font>;</center>";
	
			
		//trocando valores na coluna e base
			for($x=0; $x<count($bstabela); $x++)
			{
				if ($bstabela[$x] == $letrax)
				   $bstabela[$x] = $letray;
			}
			
		//Calculando pivô
			$pivox = $menorvalbaseX;
			$pivoy = $menorindicey;
			$pivo = $tabela[$pivox][$pivoy];
			
			echo "<center><b><hr>Valor do Pivô: <font color='blue'>".$pivo."</b></font></center>";
			
			//Seleciona o pivô e divide a linha:
			for ($y=0; $y<count($colunatab); $y++)
			{	$tabela[$pivox][$y] = $tabela[$pivox][$y]/$pivo;		}
	
				echo "<h4><b><center>Tabela após dividir pelo pivô:</h4></b></center>";
				PrintaTabela($bstabela, $colunatab, $tabela);
	
			//Mostrar tabela após dividir a linha
			echo "<hr><b><h5><center>Cálculo usando divisao na linha:</b></h5></center>";
			
			for($x=0; $x<=count($bstabela) - 1; $x++)
			{
				$valorespecial = $tabela[$x][$pivoy] * -1;
				if($valorespecial != 0 && $x != $pivox)
				{
	
					echo "<b><h4><center><font color='blue'>Linha: ". ($x + 1)."</h4></b></center>";
					echo "<h4><center>ValorEspecial: ". $valorespecial."</h4></center></font>";
					for($y=0; $y<count($colunatab); $y++)
					{
						echo "<h4><center>".$tabela[$pivox][$y]." * ".$valorespecial." + ".$tabela[$x][$y]." = ";
						$tabela[$x][$y] = (($tabela[$pivox][$y]) * $valorespecial) + $tabela[$x][$y];
						
						echo "".$tabela[$x][$y]."</h4></center>";
					}		
				}
			}
		}
		while(CondicaoParada($tabela, $colunatab, $bstabela) == false || $qtdeit<$contador);
		{
		PrintaFinais($bstabela, $colunatab, $tabela);
		echo "<hr>";
		echo "<b><h4><center>Tabela após os cálculos (Tabela Final): </center></h4></b>";
		PrintaTabela($bstabela, $colunatab, $tabela);
		}
		function PrintaFinais($bstabela, $colunatab, $tabela)
		{
			echo "<h4><center><b>Variáveis:</b></center></h4>";
			for($x=0; $x<count($bstabela); $x++)
			{
				echo "<h6><center><font color='blue'>".$bstabela[$x]." = ".$tabela[$x][count($colunatab)- 1]."</h6></center></font>";
			}
			$arraysubtract = array_diff($colunatab, $bstabela);
			foreach ($arraysubtract as &$value) 
			{
				if($value == "b")
					continue;
			}
		}
		
		function CondicaoParada($tabela, $colunatab, $bstabela)
		{
			$retorno = true;
			for($y=0; $y<count($colunatab); $y++)
			{
				if($tabela[count($bstabela) - 1][$y] < 0)
					$retorno = false;
			}
			return $retorno;
		}
		
		function PrintaTabelaMenorValor($menorvalbase)
		{
			echo "<table><tr><td><br>";
			echo "Sujeito à:  ";
			echo "<br/>";
			for($x=0; $x<count($menorvalbase); $x++) 
			{
				echo ($x+1)."* - ";
				if($menorvalbase[$x][0] === null)
				{
					echo "<center>Não efetua divisão.</center>";
				}
				else
					echo $menorvalbase[$x][1]." - ".$menorvalbase[$x][0];
				echo "<br/>";
			}
			echo "<br/></td></tr></table>";	
		}
		
		function PrintaInicio($funcao, $sa, $valorarrfinal, $qtdeit)
		{
			echo "<table><tr><td>";
			echo "<cbody>";
			echo "<center><b>Valor de Z(funcao objetiva):  </b><br><br>";
			echo $funcao;
			echo "<br/><br><b>Sujeito à: </b><br><br/>";
			for ($x=0; $x<count($sa); $x++) 
			{
				echo $sa[$x];
				echo "<b> <= </b>";			
				echo $valorarrfinal[$x];
				echo "<br/>";
			}
			echo "<br/></center>";
			echo "<b>Quantidade de Iterações que foi escolhida: <font color='blue'>".$qtdeit ."</font></b>";
			echo "<br/></td></tr></table><br/></cbody>";	
		}
		
		function PrintaTabela($bstabela, $colunatab, $tabela)
		{
			echo "<center><table><tr><th>Base</th>";
			for($x=0; $x<count($colunatab); $x++) 
			{
				echo "<th>";
				echo $colunatab[$x];
				echo "</th>";
			}		
			echo "</tr>";
					echo "<tabbody>";
			for($x=0; $x<count($bstabela); $x++) 
			{
				echo "<tr>";
				echo "<td>";
				echo $bstabela[$x];
				echo "</td>";
				for($y=0; $y<count($colunatab); $y++) 
				{
					echo "<td>";
					echo $tabela[$x][$y];
					echo "</td>";
				}	
				echo "</tr>";
			}		
			echo "</table></tabbody>";
		}
		?>
		
	<footer class="mdl-mini-footer">
		<div class="mdl-mini-footer--left-section">
			<div class="mdl-logo"> Copyright © 2016. Todos os direitos reservados.</div>
		</div>
		<div class="mdl-mini-footer--right-section">
			<div class="mdl-logo">Rodrigo Neuber</div>
			<div class="mdl-logo">|</div>
			<div class="mdl-logo">Sandra Kawakame</div>
			<div class="mdl-logo">|</div>
			<div class="mdl-logo">Talita Mendes</div>
		</div>
	</footer>
</div>
</body>
</html>	