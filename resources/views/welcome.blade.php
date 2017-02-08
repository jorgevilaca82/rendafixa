@extends('app')
@section('content')
    <div id="appCalc">

        <h2>{{ trans('simulador.title') }}</h2>
        <div class="col-md-4">
            {!! Form::open() !!}
                <div class="form-group">
                    <label for="amount">{{ trans('simulador.valor.da.aplicacao') }}</label>
                    <div class="input-group">
                        <div class="input-group-addon">R$</div>
                        <input type="number" class="form-control" id="amount" placeholder="{{ trans('simulador.valor.da.aplicacao') }}" v-model="dados.investimento" min="1"/>
                        <div class="input-group-addon">.00</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="period">{{ trans('simulador.periodo') }}</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="period" placeholder="{{ trans('simulador.periodo') }}" v-model="dados.periodo" min="1"
                               max="1200"/>
                        <div class="input-group-addon">{{ trans('simulador.meses') }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="di">{{ trans('simulador.taxa.di') }} <a href="http://www.cetip.com.br" target="_blank" title="Cetip">?</a></label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="di" placeholder="{{ trans('simulador.taxa.di') }}"
                            v-model="taxas.cdi"
                            min="0"
                            max="100"/>
                        <div class="input-group-addon">% {{trans('simulador.ao.ano')}}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="di">{{ trans('simulador.taxa.selic') }}</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="selic" placeholder="{{ trans('simulador.taxa.selic') }}"
                            v-model="taxas.selic"
                            min="0"
                            max="100"/>
                        <div class="input-group-addon">% {{trans('simulador.ao.ano')}}</div>
                    </div>
                </div>

                <!-- separador -->

                <!--
                <div class="form-group">
                    <label for="di">Taxa Administrativa</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="di" placeholder="Taxa Administrativa" value="0"
                               min="0" max="100"/>
                        <div class="input-group-addon">% ao ano</div>
                    </div>
                </div>
                -->
                <div class="form-group">
                    <label for="cdb">{{ trans('simulador.cdb') }}</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="cdb" placeholder="{{ trans('rendimento.cdb') }}" v-model="taxas.taxcdb" min="0"
                               max="100"/>
                        <div class="input-group-addon">% {{ trans('simulador.di') }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lci">{{ trans('simulador.lci.lca') }}</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="lci" placeholder="{{ trans('rendimento.lci.lca') }}" v-model="taxas.taxlci"
                               min="0" max="100"/>
                        <div class="input-group-addon">% {{ trans('simulador.di') }}</div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        <div class="col-md-offset-1 col-md-6">
            <div id="results">
                <h3>{{ trans('simulador.resultado.titulo') }}</h3>
                <p>{{ trans('simulador.resultado.descricao') }}</p>
                <hr />
                <div id="result-poupanca">
                    <h4>{{ trans('simulador.poupanca') }}</h4>
                    Valor Líquido: R$ <span class="liquido">@{{poupancaResult.toFixed(2)}}</span><br />
                    <div class="progress">
                        <div id="bar-poupanca" class="progress-bar progress-bar-danger" role="progressbar"
                             style="width: 0%; min-width: 2em;">
                            0%
                        </div>
                    </div>
                </div>
                <div id="result-cdb">
                    <h4>CDB</h4>
                    Valor Total: R$ <span class="total">@{{ cdbResult.result.toFixed(2) }}</span><br />
                    Imposto de Renda: R$ <span class="ir">@{{ cdbResult.irVal.toFixed(2) }}</span> <span class="badge">@{{ cdbResult.irIdx }}%</span><br />
                    Valor Líquido: R$ <span class="liquido">0</span><br />
                    <div class="progress">
                        <div id="bar-cdb" class="progress-bar progress-bar-info" role="progressbar"
                             style="width: 0%; min-width: 2em;">
                            0%
                        </div>
                    </div>
                </div>
                <div id="result-lci">
                    <h4>LCI</h4>
                    Valor Líquido: R$ <span class="liquido">@{{ lciResult.result.toFixed(2) }}</span><br />
                    <div class="progress">
                        <div id="bar-lci" class="progress-bar progress-bar-success" role="progressbar"
                             style="width: 0%; min-width: 2em;">
                            0%
                        </div>
                    </div>
                </div>
                <div id="result-tdselic">
                    <h4>Tesouro SELIC</h4>
                    Valor Total: R$ <span class="total">@{{ tesouroSelicResult.result.toFixed(2) }}</span><br />
                    Imposto de Renda: R$ <span class="ir">@{{ tesouroSelicResult.irVal.toFixed(2) }}</span> <span class="badge">@{{ tesouroSelicResult.irIdx }}%</span><br />
                    Taxa Bovespa: R$ <span class="bvmf">@{{ tesouroSelicResult.txBVMF.toFixed(2) }}</span><br />
                    Valor Líquido: R$ <span class="liquido">@{{ tesouroSelicResult.liquido.toFixed(2) }}</span><br />
                    <div class="progress">
                        <div id="bar-tdselic" class="progress-bar progress-bar-success" role="progressbar"
                             style="width: 0%; min-width: 2em;">
                            0%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var dados = {
            investimento: {{$amount}},
            periodo: {{ $period }}
        };

        var taxas = {
            poupanca: {{ $poupanca }},
            cdi: {{$cdi}},
            taxcdb: {{$taxcdb}},
            taxlci: {{$taxlci}},
            selic: {{$selic}}
        };
    </script>
    <script type="text/javascript" src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
    <script type="text/javascript" src="/js/calc-vue.js"></script>
    {{-- <script type="text/javascript" src="/js/calculadora.js?20151112"></script> --}}
@endsection
