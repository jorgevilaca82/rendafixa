(function () {
    var rendafixa = angular.module('rendafixa', []);

    rendafixa.controller('CalculadoraController', function ($http) {
        $http.get("/api/cdi").then(function(response){
           this.cdi = response.data;
        });
        console.log(this.cdi);
    });

    var cdiRest = {'name': 'CDI', 'value': 12.34}
})();


