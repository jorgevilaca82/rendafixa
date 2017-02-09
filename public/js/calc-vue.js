function jurosCompostos(valor, taxa, periodo) {
    return valor * (Math.pow(taxa, periodo)) - valor;
}

Vue.component('progressbar', {});

var appCalc = new Vue({
    el: '#appCalc',
    data: {
        dados: dados,
        taxas: taxas,
        barraPoupanca: { width: '0%', percent: 0 },
        barraCdb: { width: '0%', percent: 0 },
        barraLci: { width: '0%', percent: 0 },
        barraTdselic: { width: '0%', percent: 0 },
    },
    methods: {
        idxBasico: function (tx1, tx2) {
            return Math.pow((((tx1/100) * tx2) / 100 + 1), (1/12));
        },
        calcBarra: function(barra, val) {
            var percent = (val / this.dados.investimento * 100).toFixed(1);
            barra.width = (percent * 300 / this.dados.periodo);
            barra.percent = percent;
        }
    },
    computed: {
        indexBVMF: function () {
            return Math.ceil(this.dados.periodo / 6) * (0.003 / 2);
        },
        indexIR: function () {
            if (this.dados.periodo <= 6) {
                return 22.5;
            } else if (this.dados.periodo <= 12) {
                return 20;
            } else if (this.dados.periodo <= 24) {
                return 17.5;
            } else {
                return 15;
            }
        },
        poupancaResult: function () {
            var v = jurosCompostos(this.dados.investimento, (this.taxas.poupanca / 100 + 1), this.dados.periodo);
            this.calcBarra(this.barraPoupanca, v);
            return v;
        },
        lciResult: function () {
            var lciIdx = this.idxBasico(this.taxas.taxlci, this.taxas.cdi);
            var v = jurosCompostos(this.dados.investimento, lciIdx, this.dados.periodo);
            this.calcBarra(this.barraLci, v);
            return {
                result: v
            };
        },
        cdbResult: function () {
            var cdbIdx = this.idxBasico(this.taxas.taxcdb, this.taxas.cdi);
            var result = jurosCompostos(this.dados.investimento, cdbIdx, this.dados.periodo);
            var irVal = result * (this.indexIR / 100);
            var liquido = result - irVal;
            this.calcBarra(this.barraCdb, liquido);
            return {
                result: result,
                irIdx: this.indexIR,
                irVal: irVal,
                liquido: liquido
            };
        },
        tesouroSelicResult: function () {
            var selicTDIdx = Math.pow((this.taxas.selic / 100 + 1), (1/12))
            var result = jurosCompostos(this.dados.investimento, selicTDIdx, this.dados.periodo);
            var irVal = result * (this.indexIR / 100);
            var txBVMF = this.dados.investimento * this.indexBVMF;
            var liquido = (result - irVal - txBVMF);
            this.calcBarra(this.barraTdselic, liquido);
            return {
                result: result,
                irIdx: this.indexIR,
                irVal: irVal,
                txBVMF: this.dados.investimento * this.indexBVMF,
                liquido: liquido
            };
        },
    }
})
