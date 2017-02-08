function jurosCompostos(valor, taxa, periodo) {
    return valor * (Math.pow(taxa, periodo)) - valor;
}

var appCalc = new Vue({
    el: '#appCalc',
    data: {
        dados: dados,
        taxas: taxas
    },
    methods: {
        idxBasico: function (tx1, tx2) {
            return Math.pow((((tx1/100) * tx2) / 100 + 1), (1/12));
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
            return jurosCompostos(this.dados.investimento, (this.taxas.poupanca / 100 + 1), this.dados.periodo);
        },
        lciResult: function () {
            var lciIdx = this.idxBasico(this.taxas.taxlci, this.taxas.cdi);
            return {
                result: jurosCompostos(this.dados.investimento, lciIdx, this.dados.periodo)
            };
        },
        cdbResult: function () {
            var cdbIdx = this.idxBasico(this.taxas.taxcdb, this.taxas.cdi);
            var result = jurosCompostos(this.dados.investimento, cdbIdx, this.dados.periodo);
            return {
                result: result,
                irIdx: this.indexIR,
                irVal: result * (this.indexIR / 100)
            };
        },
        tesouroSelicResult: function () {
            var selicTDIdx = Math.pow((this.taxas.selic / 100 + 1), (1/12))
            var result = jurosCompostos(this.dados.investimento, selicTDIdx, this.dados.periodo);
            var irVal = result * (this.indexIR / 100);
            var txBVMF = this.dados.investimento * this.indexBVMF;
            return {
                result: result,
                irIdx: this.indexIR,
                irVal: irVal,
                txBVMF: this.dados.investimento * this.indexBVMF,
                liquido: (result - irVal - txBVMF)
            };
        },
    }
})
