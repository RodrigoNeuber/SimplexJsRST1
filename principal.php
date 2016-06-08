<!DOCTYPE HTML>
<html ng-app>
<head>
	<meta charset="utf-8">
	<title>SIMPLEX</title>
	<meta http-equiv="Content-Type" content="text/html">
	<meta name="description" content="Simplex" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-indigo.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.min.js"></script>
	<script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
	
	<style type="text/css">
		body{
		background: #fafafa;
	}
	
	.mdl-grid{
		height: 10%;
	}

	</style>
	
	<br>
</head>
<body>

	<div class=" mdl-layout mdl-js-layout mdl-layout--fixed-header ">
				<header class="mdl-layout__header">
					<div class="mdl-layout__header-row">
						<span align="center" class="mdl-layout-title">ALGORITIMO SIMPLEX R.S.T</span>
					</div>
				</header>
			
		<div ng-controller="principal" class="form">
			<form action="resultado.php" target="blank" method="POST" >
						
						<div align="center" class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
								<h3 align="center">Função Objetivo</h3>
								
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input class="mdl-textfield__input" name="funcao" type="text" id="funcaoObjetivo" tabindex="1">
								<label class="mdl-textfield__label" style="color: #000;" for="funcaoObjetivo">Z=</label>
								</div>
							</div>
						</div>
					
						<div align="center" class="mdl-grid">
							<div class="mdl-cell mdl-cell--12-col">
								<h4 align="center">Restrições e Variáveis</h4>
								
								<div ng-repeat="item in array">
									<b>S.A.: </b>
									<div class="mdl-textfield mdl-js-textfield">
										<input class="" name="sa[]" type="text" id="regras" tabindex="2" size="20" ><=<input name="suj[]" type="text" id="regras" tabindex="2" size="5">
									</div>
									<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="button" id="btnDelete" value="Deletar" ng-click="del($indice)">
								</div>
			
							</div>
						</div>
						
						<div align="center" class="mdl-grid">
							<div class="mdl-cell mdl-cell--12-col">
								<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="button" id="btnAdicionar" tabindex="-1" value="Adicionar" ng-click="add()"><i class="material-icons">add</i></button>
							</div>
						</div>
						
						<div align="center" class="mdl-grid">
							<div class="mdl-cell mdl-cell--12-col">
								<p>Número de Iterações:<p>
								<input  type="text" name="qtdeit" id="qtdeit" tabindex="3">
							</div>
						</div>
						
						<div align="center" class="mdl-grid">
							<div class="mdl-cell mdl-cell--12-col">
							<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit" name="result" id="ckbImprimirResultado" tabindex="4" value="Realizar o cálculo!" >Calcular</button>
						</div>
					
			</form>
		</div>
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

		<script type="text/javascript">
		  
			 var principal = function($scope) // cria campus estras para restrições
			{
				$scope.array = [];
		        $scope.add = function(indice) 
				{	
					$scope.array.push
				   ({ 	indexvalue: indice });
		        };
				$scope.clear = function() 
				{
					//limpa array e adiciona
					$scope.array = []; $scope.add(-1);
		        };
				$scope.del = function(indice)
				{
					$scope.array.splice(indice, 1);
					if($scope.array.length == 0)
						{ $scope.add(-1); }
				}
				$scope.add(-1);
		    }
		    </script>
		    
</body>
</html>